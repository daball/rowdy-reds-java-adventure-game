<?php
	function runCode($javaCode)
	{
		$javaCode = fixCode($javaCode);
		
		try
		{
			eval($javaCode);
		}
		catch(Exception $e)
		{
			echo "Error in code";
		}
	}
	
	function fixCode($javaCode){
		$javaCode = str_replace("int", "", $javaCode);
		$javaCode = str_replace("float", "", $javaCode);
		$javaCode = str_replace("double", "", $javaCode);
		$javaCode = preg_replace('/\s+/', '', $javaCode);
		$javaCode = replaceAllVariable($javaCode);
		
		$javaCode = str_replace("+", ".", $javaCode);
		$javaCode = str_replace("..", "++", $javaCode);
		$javaCode = str_replace("String", "", $javaCode);		
echo $javaCode;

		return $javaCode;
	}
	
	function replaceAllVariable($javaCode){
		$canBe = array("<", ";", ">", "=", "+", "*", "-", "/", "{", "}", "(", ")");
		$start = false;
		//loop through all characters
		$arr = str_split($javaCode);
		
		$varName = "";
		
		$count = 0;
		foreach($arr as $char)
		{
			if (in_array($char, $canBe, true)){
				
				if($start == true)
				{
					$start = false;
					// REPLACE WITH $ SIGN
					if(!is_numeric($varName) && ! is_bool($varName) && $varName != " ")
					{
						foreach($canBe as $itter)
						{
							foreach($canBe as $itterTwo)
							{
								$javaCode = str_replace($itter . $varName . $itterTwo, $itter."$" . $varName . $itterTwo, $javaCode);
							}
						}
						
					}
					
					
					$varName = "";
				}
				else
				{
					$count = 1;
					$start = true;
				}
			}
			
			if($start == true && $count == 0)
			{
				$varName = $varName . $char;
			}
			
			$count = 0;
		}
		$javaCode = str_replace(")$", ")", $javaCode);
		$javaCode = str_replace(";$}", ";}", $javaCode);
		$javaCode = str_replace("+$+$", "++", $javaCode);
		$javaCode = str_replace("$$", "$", $javaCode);
		$javaCode = str_replace("$$", "$", $javaCode);
		return $javaCode;
		
		
	}
	
	function replace_between($str, $needle_start, $needle_end, $replacement) {
		$pos = strpos($str, $needle_start);
		$start = $pos === false ? 0 : $pos + strlen($needle_start);

		$pos = strpos($str, $needle_end, $start);
		$end = $pos === false ? strlen($str) : $pos;

		return substr_replace($str, $replacement, $start, $end - $start);
	}
	
	function get_string_between($string, $start, $end){
		$string = ' ' . $string;
		$ini = strpos($string, $start);
		if ($ini == 0){
			return '';
		}
		else
		{
			$ini += strlen($start);
			$len = strpos($string, $end, $ini) - $ini;
			return substr($string, $ini, $len);
		}
	}
	
	echo runCode('
	for ( int i = 4;  i < 7; i++   )   
	{
		int h = 8;
		double r = 5.3;
		String s = "hello";
		double y = r + h;
		
		echo (y);
	}
	')
?>