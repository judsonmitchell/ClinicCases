<?php
function alphanumericPass()
{
    // Do not modify anything below here
    $underscores = 2; // Maximum number of underscores allowed in password
    $length = 40; // Length of password

    $p ="";
    for ($i=0;$i<$length;$i++)
    {
        $c = mt_rand(1,7);
        switch ($c)
        {
            case ($c<=2):
                // Add a number
                $p .= mt_rand(0,9);
            break;
            case ($c<=4):
                // Add an uppercase letter
                $p .= chr(mt_rand(65,90));
            break;
            case ($c<=6):
                // Add a lowercase letter
                $p .= chr(mt_rand(97,122));
            break;
            case 7:
                 $len = strlen($p);
                if ($underscores>0&&$len>0&&$len<9&&$p[$len-1]!="_")
                {
                    $p .= "_";
                    $underscores--;
                }
                else
                {
                    $i--;
                    continue;
                }
            break;
        }
    }
    return $p;
}

?>
