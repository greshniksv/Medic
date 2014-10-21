<?php

class Recombination
{

    public static function GetCombination($length)
    {
        $work = new ArrayObject();
        $fin = new ArrayObject();
        $res = new ArrayObject();
        //$length = 6;

        for ($i = 0; $i < $length; $i++) $work[$i] = 0;
        for ($i = 0; $i < $length; $i++) $fin[$i] = $length - 1;
        $stop = 0;
        $cur = 0;

        while (true) {
            $stop++;
//    if($stop>500000) break;

            if ($work[$cur] >= $length - 1) $loop = true; else $loop = false;

            while ($loop) {
                $work[$cur] = 0;
                if (@$work[++$cur] >= $length - 1) {
                    $work[$cur] = 0;
                } else {
                    $work[$cur] += 1;
                    break;
                }
            }

            $good = true;
            for ($k = 0; $k < count($work); $k++) {
                $rep = 0;
                for ($l = 0; $l < count($work); $l++) {
                    if ($work[$k] == $work[$l]) $rep++;
                }
                if ($rep > 1) $good = false;
            }


            if ($good) {
                $res[count($res)] = $work->getArrayCopy();
                $res[count($res)] = array_reverse($work->getArrayCopy());

                /*for($k=0;$k<count($work);$k++)
                {
                    echo $work[$k]."-";
                }
                echo "\n";*/
            }

            $cur = 0;
            $work[$cur] += 1;
            if ($fin == $work) {
                break;
            }
        }

        return $res;
    }
}

//print_r(GetCombination(3));



