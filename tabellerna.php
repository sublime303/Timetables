<?php

$test = new tabeller;



class tabeller{


	function tabeller(){
		echo "\n-------------------------------";
		echo "\nMultiplication table trainer";
		echo "\n learns hard tables";
		echo "\n-------------------------------";
		echo "\n";
		echo "\n 1) Multiplication  test with AI";
		echo "\n 2) Multiplication  test\n";

		$svar = self::input();
		if ($svar == 1 ){
			$resultat = self::run_multi_ai();
		}

		if ($svar == 2 ){
			$resultat = self::run_multi();
		}

		echo self::report($resultat);

	}


	private  function run_multi_ai(){
		echo "\nMultiplication AI mode";
		echo "\n Test ends when quote < 5";
		echo "\n Else progresses for ever ";
		echo "\n All questions have delay timer for report afterwards";
		echo "\n todo delay time max 3 seconds to progress";
		echo "\n else levels down";

		#todo ad AI factor progression (never ending iterations?)

		#For progression
		#$maxfactor = 2; #
		
		#Level +- 1 is for training round a level
		$level = 2; # Max factor is level
		$level_diff = 2; #scatter +- 2  not used yet
		$level_quota = 0; # +- correctness frequency ++ for right -- for fail
		#Each iteration quota is > 2 level is incremented





		$timer = array_sum(explode(' ', microtime())); #timer
		
		$num_questions = 10;
		$right = 0;
		$wrong = 0;
		$hardfactors = array();

		#for ($i = 1;$i < $num_questions +1  ;$i++){ OLD for NON AI
		$i=0;
		while($level > 1 ){
			$i++;
			$timer_q = array_sum(explode(' ', microtime())); #timer

			if($level_quota > 2){
				$level++;
				$level_quota = 0; # reset
			}

			if($level_quota <= -2){
				#echo "LOW quite!";
				$level--;
				$level_quota = 0; # reset
			}

			

			$a = rand(1,$level);
			$b = rand(1,$level);

			


			if(count($hardfactors) > 0){
				$b = array_pop($hardfactors); #move a faktor from array to next question
			}

			$svar = $a*$b;


			echo "\nQuestion[$i] Level[$level] Quota[$level_quota] Hardfactors[".count($hardfactors)."]  ->";
			foreach ($hardfactors as $key => $value) {
				echo " $value";
			}
			echo "\n$a x $b = ";
			$input = trim(self::input());
			#echo "\n du sa ".$input;

			if( $input == $svar){
				# CORRECT ANSWER
				$level_quota++;
				$right++;
				$results[$i]['result'] = 'Correct';
			}else{
				$level_quota--;
				$wrong++;
				echo " Fail.. ($svar)";
				$results[$i]['result'] = 'Fail';
				$hardfactors[]=$a;
				$hardfactors[]=$b;
				$num_questions = $num_questions + 2; #add another question to the loop

			}

			#dont allow test to end if factors are left
			if( count($hardfactors) > 0) {
				$num_questions++;
			}
			
			$results[$i]['question'] = "$a x $b = $svar ($input)";
			$results[$i]['delay'] = 100*round(  array_sum(explode(' ', microtime())) - $timer_q ,2); #ms (millseconds)

			unset($input);
		}

		$results['testreport']['total_time_ms'] = 100*round(  array_sum(explode(' ', microtime())) - $timer ,2); #ms (millseconds)
		$results['testreport']['correct'] = $right;
		$results['testreport']['questions'] = $num_questions;
		$results['testreport']['correct_procent'] = round(100*($right/$num_questions));

		echo "\n\n---------------------------";
		echo "\n\n[$right/$num_questions] correct";
		echo "\n\n---------------------------";

		return $results;
	}



	public function report($results){
		#todo
		#support results in json / serialized /  string  to support old saved resulats form db/file
		#
		echo "\nReport";
		if (is_array($results)){
			foreach ($results as $k => $v) {
				foreach ($v as $key => $value) {
					echo "\n$key: $value";
				}
			}
		}
	}




	public static  function input(){
		unset($input);
		$handle = fopen ("php://stdin","r");
		$input = fgets($handle);
		fclose($handle);
		return $input;
		// $x  = stripcslashes(trim($input));
		// return  str_replace(array("\n", "\r"), '', $x);
	}






	function run_multi(){
		echo "\nMultiplication fixed num questiions\n";
		$timer = array_sum(explode(' ', microtime())); #timer
		
		$num_questions = 4;
		$right = 0;
		$wrong = 0;
		

		for ($i = 1;$i < $num_questions +1  ;$i++){
			$timer_q = array_sum(explode(' ', microtime())); #timer

			$a = rand(2,9);
			$b = rand(2,9);
			$svar = $a*$b;

			echo "\n$a x $b = ";
			$input = trim(self::input());
			#echo "\n du sa ".$input;

			if( $input == $svar){
				#echo " yes!";
				$right++;
				$results[$i]['result'] = 'Correct';
			}else{
				$wrong++;
				echo " Fail.. ($svar)";
				$results[$i]['result'] = 'Fail';
			}
			
			$results[$i]['question'] = "$a x $b = $svar ($input)";
			$results[$i]['delay'] = 100*round(  array_sum(explode(' ', microtime())) - $timer_q ,2); #ms (millseconds)

			unset($input);
		}

		$results['testreport']['total_time_ms'] = 100*round(  array_sum(explode(' ', microtime())) - $timer ,2); #ms (millseconds)
		$results['testreport']['correct'] = $right;
		$results['testreport']['questions'] = $num_questions;
		$results['testreport']['correct_procent'] = round(100*($right/$num_questions));

		echo "\n\n---------------------------";
		echo "\n\n[$right/$num_questions] correct";
		echo "\n\n---------------------------";

		return $results;
	}
}


