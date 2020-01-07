<?php
namespace Models;
class Myutils
{
    public static function is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)
    {
        $i = $j = $c = 0;
        for ($i = 0, $j = $points_polygon-1 ; $i < $points_polygon; $j = $i++) {
            if ( (($vertices_y[$i] > $latitude_y != ($vertices_y[$j] > $latitude_y)) &&
                ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i]) ) )
                $c = !$c;
        }
        return $c;
    }
    public static function convertNumbers($srting,$toPersian)
    {
        $en_num = array('0','1','2','3','4','5','6','7','8','9');
        $fa_num = array('۰','۱','۲','۳','۴','۵','۶','۷','۸','۹');
        if( $toPersian ) return str_replace($en_num, $fa_num, $srting);
        else return str_replace($fa_num, $en_num, $srting);
    }
    public static function distance($lat1, $lon1, $lat2, $lon2, $unit)
    {
        if (($lat1 == $lat2) && ($lon1 == $lon2))
        {
            return 0;
        }
        else
        {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K")
            {
                return ($miles * 1.609344);
            }
            else if ($unit == "N")
            {
                return ($miles * 0.8684);
            }
            else
            {
                return $miles;
            }
        }
    }
    public static function createRand($lenght,$table="",$col="",$chars=0,$prefix="")
    {
        switch ($chars)
        {
            case 0:
                $possibleletters="123456789";
                break;
            case 1:
                $possibleletters="ABCDEFGHIJKLMNOP";
                break;
            case 2:
                $possibleletters="123456789ABCDEFGHIJKLMNOP";
                break;
        }
        while (1)
        {
            $i=0;
            $code=$prefix;
            while ($i < $lenght)
            {
                $code .= substr($possibleletters, mt_rand(0, strlen($possibleletters)-1), 1);
                $i++;
            }
            if(!empty($table))
            {
                $db=new db;
                $db->settbl($table);
                $r=$db->select_data($col,$code);
                if(sizeof($r)==0)
                {
                    break;
                }
            }
            else
            {
                break;
            }
        }
        return $code;
    }
    public static function getNext($table,$col="",$min=1)
    {
        if (empty($col))
        {
            $db=new db;
            $s=$db->directQuery("SHOW TABLE STATUS LIKE '$table'");
            $s=$s[0];
            return $s->Auto_increment;
        }
        else
        {
            $db=new db;
            $s=$db->directQuery("select max($col) as mx from $table");
            $ret=$s[0]->mx;
            if(empty($ret))
            {
                $ret=$min;
            }
            else
            {
                $ret+=1;
            }
            return $ret;
        }
    }
    public static function my_dump($thing)
    {
        echo "<pre>";
        print_r($thing);
        echo "</pre>";
    }
}



?>