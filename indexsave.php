<?php
$result = array();

/* create list of array for individual test case value */
$i = 1;
foreach($_POST['txtbox'] as $value) {
    $result[$i] = str_split(trim($value));
    $i++;
}

/* create base mapping for array template start with 1 for keys*/
$ij = 1;
foreach($result as $key => $value) {
    $j = 0;
    $k = 1;
    foreach($value as $key2 => $value2) {
        if($value2==1) {
            $j+=1;
            $result[$ij]['test_case'] = $ij;
            $result[$ij]['total'] = $j;
            $result[$ij]['key_1'][$k] = $value2;
        }else {
            $result[$ij]['key_0'][$k] = $value2;
        }
        unset($result[$ij][$k]);
        unset($result[$ij][0]);
        $k++;
    }
    $ij++;
}

/* push test case array into template array for sorting purpose */
$template = array();
foreach ($result as $key => $row) {
    $template[$key]['total'] = $row['total'];
    $template[$key]['test_case'] = $row['test_case'];
    $template[$key]['key_1'] = $row['key_1'];
    $template[$key]['key_0'] = $row['key_0'];
}

/* sort highest test case total at the top on array[1] in descending */
array_multisort($template, SORT_DESC, $result);
array_unshift($template, "0");
unset($template[0]);

/* compare array[1] with other test case array to satisfy Greedy Algorythm Test*/
foreach($template as $item => $value) {
    if (is_array($value) && $item > 1) {
        for($i=0;$i<=19;$i++) {
            if($template[1]['key_0'][$i] == 0 && $template[$item]['key_1'][$i] == 1) {
                if($template[1]['key_0'][$i] !== $template[$item]['key_1'][$i]) {
                    unset($template[1]['key_0'][$i]);
                    if($template[1]['key_0'] == null){
                        //$template[$item]['mode'] = 'stop';
                    }else{
                        $template[1]['mode'] = 'run';
                        $template[$item]['mode'] = 'run';
                    }
                }
            }
        }
    }
}

/*After Reduction*/
$mode = array();
foreach ($template as $key => $value) {
    if(is_array($value) && $template[$key]['mode'] == 'run'){
        array_push($mode, $template[$key]['test_case']);
    }  
}
/* print final result */
echo '<pre>';
//print_r($template);
//print_r($mode);
//print_r($result);
echo '</pre>';

echo 'Largest requirement cover : '.$template[1]['test_case'].'('.$template[1]['total'].')';

echo '<br/>Remaining requirement are : '.implode(array_keys($result[0]['key_0']),',') ;

echo '<br/>All test case : '.implode(array_keys($template),',') ;

echo '<br/>After Reduction : '.implode(array_values($mode),',') ;
?>