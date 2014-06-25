<!--
<input type=text name='sampleval' id='sampleval' onkeyup='calculateNumber(this.value,"valueWords")' />
<textarea name='valueWords' id='valueWords' ></textarea>
<input type=button value='press me' onclick="alert('a')" />
-->
<script language="javascript">
function calculateNumber(number,elementName){
	var word=" ";
	if((number%1)>0){
		var terms=new Array();
		
		var digits=new Array();
		terms[1]=roundNumber(number%1*100);
		terms[0]=calculateWholeNumber(roundNumber(number/1)-roundNumber(number%1));
		
		if(terms[0]==0){
		word=terms[1]+"/100 ";
		
		}
		else {
		word=terms[0]+" Pesos and "+terms[1]+"/100 ";
		
		}

	}
	else {
		word=calculateWholeNumber(number)+ " Pesos";
	
	}
	

	document.getElementById(elementName).value=word;

}


function calculateWholeNumber(number){
	number=roundNumber(number);
	
	var word="";
	if(number>0){
		if(number<20){
			word=oneDigits(number);

		}
		else {
			if(number<100){
				word=lessHundred(number);
				
				
				
			}
			else {
				if(number<1000){
					word=lessThousand(number);
				}
				else {
					if(number<1000000){
						word=lessMillion(number);
						
					}
					else {
						if(number<1000000000){
							var terms=new Array();
							var digits=new Array();
							
							digits[0]=number/1000000-((number%1000000)/1000000);
							digits[1]=number%1000000;
							
							if(digits[0]<1000){
								terms[0]=lessThousand(digits[0]);
							
							}
							if((number%1000000)>0){
								if(digits[1]<1000){
									terms[1]=lessThousand(digits[1]);
								}
								else {
									terms[1]=lessMillion(digits[1]);
								}
							}

							word=terms[0]+" Million";
							if(digits[1]==0){
							}
							else {
								word+=" "+terms[1];
							}
							
							
						}
					
					}
				
				}


			}
			
			
		
		}

	}
	
	return word;
	
	
}

function lessMillion(number){
	var word="";
	var terms=new Array();

	terms[0]=lessThousand(roundNumber(number/1000)-roundNumber((number%1000)/1000));
	

	if(number%1000>0){
		terms[1]=lessThousand(number%1000);
		word=terms[0]+" Thousand, "+terms[1];
	}
	else {
		word=terms[0]+" Thousand";
	}
	return word;
}

function lessThousand(number){
	number=roundNumber(number);

	if(number>99){
		var word="";
		
		var hundreds=number/100;
		
		hundreds=hundreds-((number%100)/100);
		hundreds=roundNumber(hundreds);
		var wordPrefix=oneDigits(hundreds);
		if(wordPrefix=="zero"){
			wordPrefix=" ";
		
		}
		else {
			wordPrefix+=" Hundred ";
		}
		if(number%100>0){
		
			word=wordPrefix+lessHundred(number%100);
		}
		else {
			word=wordPrefix;
		}
		
	}
	else {
		word=lessHundred(number);
	
	}
	return word;

}



function lessHundred(number){
	number=roundNumber(number);
	var word=" ";
	var decimal=number%10;
	var whole=number;
	var tens=0;
	var digit=0;

	if(number<20){
		word=oneDigits(number);
	
	}
	else {
		if(decimal>0){

			tens=whole/10-((whole%10)/10);
			digit=(whole%10);	
			
			
			tens=roundNumber(tens);
			
			word=calculateTens(tens);
			word=word+"-"+oneDigits(digit);
					
					
		}
		else {
			
			tens=whole/10;
			word=calculateTens(tens);

		}
	}
	return word;


}





function oneDigits(number){
	var digit=new Array();
	digit[0]="zero";
	digit[1]="One";
	digit[2]="Two";
	digit[3]="Three";
	digit[4]="Four";
	digit[5]="Five";
	digit[6]="Six";
	digit[7]="Seven";
	digit[8]="Eight";
	digit[9]="Nine";
	digit[10]="Ten";
	digit[11]="Eleven";
	digit[12]="Twelve";
	digit[13]="Thirteen";
	digit[14]="Fourteen";
	digit[15]="Fifteen";
	digit[16]="Sixteen";
	digit[17]="Seventeen";
	digit[18]="Eighteen";
	digit[19]="Nineteen";
		
	return digit[number];	
}

function calculateTens(number){
	var digit=new Array();
	digit[2]="Twenty";
	digit[3]="Thirty";
	digit[4]="Forty";
	digit[5]="Fifty";
	digit[6]="Sixty";
	digit[7]="Seventy";
	digit[8]="Eighty";
	digit[9]="Ninety";
	
	return digit[number];
	
}

function roundNumber(number){
	

	number=Math.round(number*1000)/1000;
	return number;

}

</script>