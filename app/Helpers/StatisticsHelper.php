<?php

namespace App\Helpers;
use App\Upload;
use File;
use App\Ticket;
use DB;
use State;
use App\TopUser;

class StatisticsHelper 
{
    
    /**
     * GET ELOQUENT INSTANCE OF A TICKET ON A CERTAIN DATES AND STATE THAT IS CLOSED
     */
    public static function query_ticket($factory=null,$catered_by=null,$created_from=null,$created_to=null)
    {
        $tickets=Ticket::on();

        if($created_from!=null && $created_to!=null)
            $tickets->whereRaw(DB::raw("date(tickets.created_at) between '".$created_from."' and '".$created_to."'"));
        if($catered_by!=null)
            $tickets->where('tickets.catered_by',$catered_by);
        if($factory!=null)
            $tickets->where('tickets.factory',$factory);

        $tickets->where('tickets.state','<>',State::CANCELLED);    
        
        
        return $tickets;
    }
    /**
     * Get the summarize of ratings in a week
     */
    public static function rating_summary($factory=null,$catered_by=null,$created_from=null,$created_to=null)
    {
        # Count the tickets that has no rating
        $no_star=static::query_ticket($factory,$catered_by,$created_from,$created_to)
            ->where('state',State::CLOSED)
            ->whereNull('user_rating')->count();

        $one_star=static::query_ticket($factory,$catered_by,$created_from,$created_to)
            ->where('state',State::CLOSED)
            ->where('user_rating',1)->count();

        $two_star=static::query_ticket($factory,$catered_by,$created_from,$created_to)
            ->where('state',State::CLOSED)
            ->where('user_rating',2)->count();

        $three_star=static::query_ticket($factory,$catered_by,$created_from,$created_to)
            ->where('state',State::CLOSED)
            ->where('user_rating',3)->count();

        $four_star=static::query_ticket($factory,$catered_by,$created_from,$created_to)
            ->where('state',State::CLOSED)
            ->where('user_rating',4)->count();

        $five_star=static::query_ticket($factory,$catered_by,$created_from,$created_to)
            ->where('state',State::CLOSED)
            ->where('user_rating',5)->count();
        
        
        return [
            'no'=>$no_star,
            'one'=>$one_star,
            'two'=>$two_star,
            'three'=>$three_star,
            'four'=>$four_star,
            'five'=>$five_star,
        ];
    }

    public static function top_category_request($number,$factory=null,$catered_by=null,$created_from=null,$created_to=null)
    {
        $tickets=static::query_ticket($factory,$catered_by,$created_from,$created_to);
        $tickets->select(['Category',DB::raw("count(Category) as Number")])
        ->whereIn('state',[State::SOLVED,State::CLOSED])
        ->groupBy('Category')
        ->OrderByRaw(DB::raw("count(Category) desc"))
        ->limit($number);

        return $tickets->get()->makeHidden(['last_state'])->toArray();
    }

    public static function top_users($number,$factory=null,$created_from=null,$created_to=null)
    {
        $users=TopUser::on();
        if($factory!=null)
            $users->where('factory',$factory);
        if($created_from!=null && $created_to!=null)
        {
            $users->with([
                'count_ratings'=>function($query)use($created_from,$created_to)
                {
                    return $query->select(['count(*)'])->whereRaw(DB::raw("date(created_at) between '".$created_from."' and '".$created_to."'"));
                }
            ]);
        }
        else
        {
            $users->with([
                'count_ratings'=>function($query)use($created_from,$created_to)
                {
                    return $query->select([DB::raw("count(*)")]);
                }
            ]);
        }

        if($number!=null)
            $users->limit($number);


        return $users->get();
    }


}
