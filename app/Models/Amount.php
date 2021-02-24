<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Amount extends UuidModel
{
    use HasFactory;

    protected $fillable = ['friend_id', 'event_id', 'amount', 'note', 'private', 'archive_flg'];

    //protected $with = ['deals'];

    public function event()
    {
        return $this->belongsTo(
            Event::class
        );
    }

    public function line_friend()
    {
        return $this->belongsTo(
            LineFriend::class, 
            'friend_id',
            'line_id'
        );
    }

    public function deals()
    {
        return $this->hasMany(
            Deal::class,
        );
    }

    /**
     * @param $allAmounts array 全ての支払いデータ
     * @param $participants object イベントの参加者一覧
     * @param $approvedTransactions 承認済みの割り勘代支払い一覧
     * @param $amountPerUser array 返り値：ユーザーごとの合計支払い金額データ
     * @param $privateDeals array 個人間の貸し借りのデータ
     * @param $payerDeals array ユーザーごとの個人間の支払った額のデータ
     * @param $partnerDeals array ユーザーごとの個人間の支払われた額のデータ
     * @param $joinedDeals array 各ユーザーの個人間の貸し借りのデータ
     * @param $eachPayerDealsPerPartner array 各ユーザーのpartner別の貸し借りのデータ
     */
    public static function getEachUserAmount($event_id)
    {
        $allAmounts = json_decode(static::where('event_id', $event_id)->with(['line_friend', 'deals'])->get());
        $participants = Event::where('id', $event_id)->get()->first()->line_friends;
        $approvedTransactions = Transaction::where('event_id', $event_id)->where('approved', true)->get();
        $notPrivateAmounts = array_filter(
            $allAmounts,
            function($value){
                return $value->private == false;
            }
        );
        $privateDeals = array_filter(
            $allAmounts,
            function($value){
                return $value->private == true;
            }
        );

        $payerDeals = [];
        $partnerDeals = [];
        $amountPerUser = [];

        foreach($participants as $participant){
            $otherParticipants = array_filter(
                json_decode($participants),
                function($value) use($participant){
                    return $value->line_id !== $participant->line_id;
                }
            );
            $eachPayerDeal = static::getPrivateDealPerUser($privateDeals, 'payer', $participant, $otherParticipants);
            $eachPartnerDeal = static::getPrivateDealPerUser($privateDeals, 'partner', $participant, $otherParticipants);
            $joinedDeals = static::joinPayerDealAndPartnerDeal($eachPayerDeal, $eachPartnerDeal, $otherParticipants);

            $notPrivateUnarchivedAmounts = [];
            $notPrivateArchivedAmounts = [];

            foreach($notPrivateAmounts as $amount){
                if($amount->archive_flg == 0){
                    array_push($notPrivateUnarchivedAmounts, $amount);
                }else{
                    array_push($notPrivateArchivedAmounts, $amount);
                }
            }

            $archivedAmountsSum = static::calcSum($notPrivateArchivedAmounts);
            $archivedAmountsSum = ceil($archivedAmountsSum / count($participants));
            
            $matchData = array_filter(
                $notPrivateUnarchivedAmounts,
                function($value) use($participant){
                    return $participant->line_id == $value->friend_id;
                }
            );
            $sum = static::calcSum($matchData);

            foreach($approvedTransactions as $approvedTransaction){
                if($approvedTransaction->from_user === $participant->line_id){
                    $sum += $approvedTransaction->amount;
                }else if($approvedTransaction->to_user == $participant->line_id){
                    $sum -= $approvedTransaction->amount;
                }
            }

            $pushData = [
                'line_friend' => $participant,
                'sum' => $sum + $archivedAmountsSum,
                'deals' => $joinedDeals
            ];
            array_push($amountPerUser, $pushData);
        }

        return $amountPerUser;
    }

    public static function calcSum($data)
    {
        $sum = 0;
        if(count($data) > 0){
            foreach($data as $item){
                $amountData = $item->amount;
                $sum += $amountData;
            }
        }
        return $sum;
    }

    public static function getPrivateDealPerUser($privateDeals, $type, $participant, $otherParticipants)
    {
        $eachUserDeals = [];
        $eachUserDealsPerOther = [];
        $category = $type == 'payer' ? 'partner' : 'payer';

        foreach($privateDeals as $deal){ //Amount $privateDealsの中から$typeがline_idのものを選ぶ
            $targetDeals = array_filter(
                $deal->deals,
                function($value) use($participant, $type){
                    return $participant->line_id == $value->$type;
                }
            );
            if(count($targetDeals) > 0){
                foreach($targetDeals as $item){
                    array_push($eachUserDeals, $item);
                }
            }
        }

        $eachParticipantDeal = [];
        foreach($otherParticipants as $other){ //$categoryごとに合計支払額をだし配列に入れる
            $targetDeals = array_filter(
                $eachUserDeals,
                function($value) use($other, $category){
                    return $value->$category == $other->line_id;
                }
            );
            $sum = static::calcSum($targetDeals);
            $pushData = [
                'partner' => $other,
                'sum' => $sum
            ];
            array_push($eachParticipantDeal, $pushData);
        }

        return $eachParticipantDeal;
    } 

    public static function joinPayerDealAndPartnerDeal($eachPayerDeal, $eachPartnerDeal, $otherParticipants)
    {
        $deals = [];
        foreach($otherParticipants as $other){
            $payerDeal = static::getMatchPartnerItem($eachPayerDeal, $other)[0];
            $partnerDeal = static::getMatchPartnerItem($eachPartnerDeal, $other)[0];
            $paySum = $payerDeal['sum'];
            $paidSum = $partnerDeal['sum'];

            if($paySum !== 0 || $paidSum !== 0){
                $deal = [
                    'partner' => $payerDeal['partner'],
                    'pay_sum' => $payerDeal['sum'],
                    'paid_sum' => $partnerDeal['sum']
                ];
                array_push($deals, $deal);
            }
        }
        return $deals;
    }

    public static function getMatchPartnerItem($data, $query)
    {
        $matchItem = [];
        foreach($data as $item){
            if($item['partner']->line_id == $query->line_id){
                array_push($matchItem, $item);
            }
        }
        return $matchItem;
    }
}
