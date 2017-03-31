<?php

class Helpers
{

	public static function interval($from,$to,$time)
	{
		if ($time && $from) {
			return $from->format('j. n. Y').' v '.$time->format('%H.%I');
		} elseif ($from && $to) {
			if (date('Y',strtotime($from))!=date('Y',strtotime($to))) {
				return date('j. n. Y',strtotime($from)).'–'.date('j. n. Y',strtotime($to));
			} elseif (date('n.',strtotime($from))!=date('n.',strtotime($to))) {
				return date('j. n.',strtotime($from)).'–'.date('j. n. Y',strtotime($to));
			} else {
				return date('j.',strtotime($from)).'–'.date('j. n. Y',strtotime($to));
			}
		} elseif ($from) {
			return date('j. n. Y',strtotime($from));
		} else {
			return 'Chybně zadané datum';
		}
		if (date('Y',strtotime($from)).'*'.date('Y',strtotime($to)))
		return date('j.n.Y',strtotime($from)).'*'.date('j.n.Y',strtotime($to)).'**'.date('H:i:s',strtotime($time));
	}
	
	public static function tags($text,$tags)
	{
		return strip_tags($text,$tags);
	}

}
