<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Transforma serie numerica do preco em outros tipos de serie temporal
 *  como rsi, media movel simples
 */
class SerieUtils
{
    /**
     * 
     * @param array $lst passa a serie completa sem sobra
     * @param int $season periodo do rsi
     */
    public static function rsi($lst, $season)
    {
        
            $out = array();
            $sumWin = 0.0;
            $sumLost = 0.0;
            $current; $old; $averageWin; $averageLost; $rsiValue;
            $divider = (float)$season;
            

            $left = 0;
            $right = $season + 1;
            $max = count($lst) - $season;
            for($i=0; $i<$max; $i++)
            {
                $sumLost = 0.0;
                $sumWin = 0.0;
                for($j=$left; $j<$right; $j++)
                {
                    if($j == $left)
                    {
                        continue;
                    }
                    $current = $lst[$j];
                    $old = $lst[$j-1];

                    if($current > $old)
                    {
                        $sumWin += $current - $old;
                    } else 
                    {
                        $sumLost += -1.0 * ($current - $old);
                    }
                }

                $averageLost = $sumLost / $divider;
                $averageWin = $sumWin / $divider;
                
                
                $rsiValue = 100.0 * ($averageWin / ($averageWin + $averageLost));
                

                $right++;
                $left++;
                $out[] = $rsiValue;
            }
            
        
        

        return $out;
    }
    
    /**
     * Simple moving average
     * @param array $lst float values of price
     * @param int $season
     * @return array the size is not the same, len($lst) - $season
     */
    public static function sma($lst, $season)
    {
        $out = array();
        $sum = 0.0;
        $average = 0.0;
        $current = 0.0;
        $divider = (float)$season;
        
        $left = 0;
        $right = $season + 1;
        $max = count($lst) - $season;
        for($i=0; $i<$max; $i++)
        {
            $sum = 0.0;
            for($j=$left; $j<$right; $j++)
            {
                
                $current = $lst[$j];
                $sum += $lst[$j];
            }
            $average = $sum / $divider;
            
            $right++;
            $left++;
            $out[] = $average;
        }
        
        return $out;
    }
}
