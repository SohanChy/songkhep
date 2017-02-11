<?php

class Stemmer {
	
	private $lines = [];
	private $pass = [];
	
	private $st = [];
	private $escape = [];
	private $replaceRule = [];


	public function __construct($path)
	{
		$this->dependantCharSetInstallation();
		// escapeOfRuleInstallation();

			$line = null;

			if ($file = fopen($path, "r")) {
			    while(!feof($file)) {
			        $line = fgets($file);
			       
			       	// echo "[og] -> ".$line."<br>";
			        
			    	$line=$this->commentTrim($line);
			        $line=$this->whiteSpaceTrim($line);

			    	if($line == ""){
			    		continue;	
			    	} 

			 		// echo $line."<br>";


			    	$replace= $this->extractReplaceRule($line);

			    	$line=mb_ereg_replace("->.*", "",$line);
			    	if($replace != "")
			    	{
			    		$this->replaceRule[$line] = $replace;
			    	}
			    	

			    	$this->lines[] = $line;

			    }
			    fclose($file);
			}
			else {
				echo "file read failed";
			}			
	
		
		$cnt=0;

		for($i=0;$i<count($this->lines);$i++)
		{
			if($this->lines[$i] == "{")
			{
				$this->pass[$cnt] = [];
				$i++;
				
				while($i<count($this->lines) && $this->lines[$i] != "} ")
				{
					$this->pass[$cnt][] = $this->lines[$i];
					$i++;
				}
				$cnt++;
			}
		}

	

/*		//modified here
		echo "line";
		var_dump($this->lines);
		echo "<hr>pass";
		var_dump($this->pass);
		echo "<hr>replaceRule";
		var_dump($this->replaceRule);
		
		//modified outside
		echo "<hr>st";
		print_r($this->st);
		echo "<hr>escape";
		print_r($this->escape);*/

		
	}

	
	private function whiteSpaceTrim($str)
	{
		$do = mb_ereg_replace("[\t' ']+", "",$str);
		$do = str_replace(" ","",$do);
		$do = trim($str);
		return $do;
	}
	
	private function commentTrim($str)
	{
		return mb_ereg_replace("#.*", "",$str);
	}
	
	private function extractReplaceRule($str)
	{
		if(mb_ereg_match(".*->.*",$str))
		{
			$l = explode("->",$str);
			return $l[1];
		}
		return "";
	}

	private function dependantCharSetInstallation()
	{
		$this->st[] = 'া';
		$this->st[] = 'ি';
		$this->st[] = 'ী';
		$this->st[] = 'ে';
		$this->st[] = 'ু';
		$this->st[] = 'ূ';
		$this->st[] = 'ো';
	}
	
	public function stemOfWord($word)
    {
        $i=0;
        $j=0;

        for($i=0;$i<count($this->pass);$i++)
        {
            for($j=0;$j< count($this->pass[$i]);$j++)
            {

                $replacePrefix = $this->pass[$i][$j];

                $matcher = ".*" . $replacePrefix . "$";

                if(mb_ereg_match($matcher,$word))
                {
                    $indx = strlen($word) - strlen($replacePrefix);

                    if(in_array($replacePrefix,$this->replaceRule))
                    {
                        $replaceSuffix = $this->replaceRule[$replacePrefix];

                        $builder = $word;
                        $k=0;
                        $l=0;
                        for($k=$indx,$l=0;$k<$indx+strlen($replaceSuffix);$k++,$l++)
                        {
                            if($replaceSuffix[l]!='.')
                            {
                                $builder[k] = $replaceSuffix[l];
                            }
                        }
                        $word= substr($builder,0, $k);
                    }

                    else if(/* escape.contains(pass.get(i).get(j)) || */ 
                    	$this->check(substr($word,0,$indx)))
                    {
                        $word=substr($word,0,$indx);
                    }

                    break;
                }
            }
        }

        return $word;
    }

    private function check($word)
	{
		$i;
		$wordLength = 0;
		
		for($i=0;$i<strlen($word);$i++)
		{
			if(in_array($word[$i],$this->st)){
				continue;
			} 
			$wordLength++;
		}
		
		return $wordLength >= 1;
	}
	
}
	
// //	private void escapeOfRuleInstallation()
// //	{
// //		escape=new TreeSet<String>();
// //		escape.add("চ্ছি");
// //		escape.add("চ্ছিল");
// //		escape.add("চ্ছে");
// //		escape.add("চ্ছিস");
// //		escape.add("চ্ছিলেন");
// //		escape.add("টি");
// //		escape.add("টা");
// //		escape.add("েরটা");
// //		escape.add("গুলো");
// //	}
	

	
// }

?>