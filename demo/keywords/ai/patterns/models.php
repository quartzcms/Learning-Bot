<?php
/* The syntax pattern in case no pattern is found. They are used to reorder sentences in new sentences */

$sentenceVariants = array();
$builtSentences = array();
$reorderedWords = array();
$sentenceOrder = array();
$syntaxModel = array();

/* INFINITIF VERBS */
$syntaxModel[] = 'ver_inf,art_def|adj_num|adj_pos,nom|other**1,adj+**1,pro_int,art_def,nom**1,adj+**1,question';
$syntaxModel[] = 'ver_inf,lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,adj+,art_def,art_def+,nom**1,question';
$syntaxModel[] = 'ver_inf,lia|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adj**1,question';
$syntaxModel[] = 'pro_per,pro_per_con+,ver,adv+,lia,pro_int,art_def,art_def+,nom|other**1,adj+**1,question';

$syntaxModel[] = 'ver_inf,art_def|adj_num|adj_pos,nom|other**1,adj+**1,art_def,nom**1,adj+**1,question';
$syntaxModel[] = 'ver_inf,lia|art_def|adj_num|adj_pos|pro_ind,nom|other**1,art_def,art_ind,nom**1,adj**1,question';
$syntaxModel[] = 'adv+,ver_inf,lia|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adj+**1,question';
/* END OF INFINITIF VERBS */

/* PAST PARTICIPLE */
/* If no verb in the sentence */
if(empty($wordsByType['ver'])){
	$syntaxModel[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,pro_per_con+,aux*1,pro_per*1,adv+,ver_past**3,art_def,art_def+,nom**1,adj+**1,question';
	$syntaxModel[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,pro_per_con+,aux*1,pro_per*1,adv+,ver_past**3,art_ind,nom**1,adj+**1,question';
	$syntaxModel[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,pro_per_con+,aux*1,pro_per*1,adv+,ver_past**3,art_def,nom**1,adj+**1,dot';
	$syntaxModel[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,pro_per_con+,aux*1,pro_per*1,adv+,ver_past**3,question';
	$syntaxModel[] = 'art_ind|art_def|adj_num|adj_pos,nom|other**1,adj+**1,pro_per_con+,aux*1,pro_per*1,adv+,ver_past**3,question';
	$syntaxModel[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,pro_per_con+,aux*1,adv+,ver_past**2,art_ind,nom**1,adj+**1,dot';
	$syntaxModel[] = 'pro_per_con+,aux,pro_per*1,adv+,ver_past**1,art_ind|art_def|adj_num|adj_pos,nom|other**2,adj+**1,question';
	$syntaxModel[] = 'art_def,art_def+,nom|other**1,adj+**1,pro_per_con+,aux*1-2,pro_per*1,adv+,ver_past**3,question';
	$syntaxModel[] = 'pro_per_con+,aux,pro_per*1,adv+,ver_past**1,art_def,art_def+,nom|other**2,adj+**1,question';
	$syntaxModel[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,pro_per_con+,aux*1-2,adv+,ver_past**2,dot';
	$syntaxModel[] = 'art_ind|art_def|adj_num|adj_pos,nom|other**1,adj**1,pro_per_con+,aux*2-2,adv+,ver_past**3,dot';
}
$syntaxModel[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adj+,aux*1-2,ver_past**2,art_def,art_def+,nom**1,adj+**1,question';
$syntaxModel[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,adj+,aux*1-2,ver_past**2,art_ind,nom**1,adj+**1,question';
$syntaxModel[] = 'adv+,lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adj+,aux*1-2,ver_past**2,question';
$syntaxModel[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,aux*1-2,adv+,ver_past**2,art_def,art_def+,nom**1,dot';
$syntaxModel[] = 'art_def,art_def+,nom|other**1,adj+**1,ver_past**1,other,question';
$syntaxModel[] = 'adv+,ver_past,art_def,art_def+,nom|other**2,adj+**1,question';
$syntaxModel[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,ver_past**1,dot';
$syntaxModel[] = 'other,art_ind|art_def|adj_num|adj_pos,nom|other**1,adj**1,adv+,ver_past**2,dot';

/* END OF PAST PARTICIPLE */

if ($actions[$randomIndex] == 'rep') {
	$syntaxModel[] = 'art_def|adj_num|adj_pos,other,adj+**2,ver,pro_per_con+,pro_per*1,adv+,art_def,art_def+,nom|other**1,question';
	$syntaxModel[] = 'lia|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adj**1,ver|aux*2-2,pro_per_con+,pro_per*1,adv+,art_def,art_def+,nom**1,question';
	$syntaxModel[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,ver|aux*1-2,pro_per_con+,pro_per*1,adv+,art_def,art_def+,nom**1,question';
	$syntaxModel[] = 'lia|art_def|adj_num|adj_pos|pro_ind,nom|other**1,ver|aux*1-1,pro_per_con+,pro_per*1,adv+,art_ind,nom**1,adj**1,question';
	$syntaxModel[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,ver,pro_per_con+,pro_per*1,ver_inf,art_def,art_def+,nom**1,question';
	/* If no verb in the sentence */
	if(empty($wordsByType['ver'])){
		$syntaxModel[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,aux*1-2,pro_per*1,adv+,art_def,art_def+,nom**1,question';
		$syntaxModel[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,aux*1-1,pro_per*1,art_ind,nom**1,adj**1,question';
		$syntaxModel[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,aux*1-1,pro_per*1,adj**3,question';
	}
	$syntaxModel[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,ver,pro_per_con+,pro_per*1,adv+,art_def|art_ind,art_def+,nom**1,adj+,question';
	$syntaxModel[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,ver|aux*1-2,pro_per_con+,pro_per*1,adv+,art_ind,nom**1,question';
} elseif ($actions[$randomIndex] == 'sug') {
	/* If no verb in the sentence */
	if(empty($wordsByType['ver'])){
		$syntaxModel[] = 'pro_per,aux*1,adv+,ver_past**2,lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**2,adj**1,art_def,art_def+,nom**1,dot';
		$syntaxModel[] = 'pro_per,pro_per_con+,aux*1,ver_past**2,lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**2,adj**1,dot';
	}
	$syntaxModel[] = 'adv+,pro_per,ver**1,art_def|adj_num|adj_pos,other,adj+,art_def,art_def+,nom|other**1,dot';
	$syntaxModel[] = 'adv+,lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,ver**1,art_ind,nom**1,adj+**1,dot';
	$syntaxModel[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,ver**1,art_def,art_def+,nom**1,adj+**1,dot';
	$syntaxModel[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,ver**1,adv+,art_ind,nom**1,dot';
	$syntaxModel[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,ver**1,adv+,art_def,art_def+,nom**1,dot';
	$syntaxModel[] = 'lia|art_def|adj_num|adj_pos|pro_ind,nom|other**1,pro_per,pro_per_con+,ver|aux*1-2,art_def,art_def+,nom**1,dot';
	$syntaxModel[] = 'lia|art_def|adj_num|adj_pos|pro_ind,nom|other**1,pro_per,pro_per_con+,ver|aux*1-2,art_ind,nom**1,dot';
}


/* PAST PARTICIPLE */
/* If no verb in the sentence */
if(empty($wordsByType['ver'])){
	$syntaxModel[] = 'pro_per,pro_per_con+,aux*1,ver_past**2,art_def,art_def+,nom|other**2,adj+**1,dot';
	$syntaxModel[] = 'pro_per_con+,aux,pro_per*1,adv+,ver_past**1,art_ind,nom|other**2,adj+**1,question';
	$syntaxModel[] = 'art_def,art_def+,nom|other**1,pro_per_con+,aux*1-2,adv+,ver_past**2,dot';
	$syntaxModel[] = 'pro_per,pro_per_con+,aux*1,ver_past**2,art_ind,nom|other**2,adj+**1,dot';
	$syntaxModel[] = 'pro_per|pro_dem|pro_ind,pro_per_con+,aux*1-2,ver_past**2,dot';
	$syntaxModel[] = 'pro_per_con+,aux,pro_per*1,ver_past**1,adv+,question';
}
/* END OF PAST PARTICIPLE */

/* INFINITIF VERBS */
$syntaxModel[] = 'ver_inf,ver_inf+,art_def|adj_num|adj_pos,nom|other**1,adj+**1,question';
$syntaxModel[] = 'adv+,ver_inf,ver_inf+,art_def|adj_num|adj_pos,nom|other**1,question';
$syntaxModel[] = 'adv,ver_inf,ver_inf+,art_def,nom|other**1,adj+**1,question';
$syntaxModel[] = 'pro_per|pro_dem|pro_ind,pro_per_con+,ver,adv+,con,ver_inf,adj+,question';
$syntaxModel[] = 'ver_inf,art_def,art_def+,nom|other**1,question';
$syntaxModel[] = 'ver_inf,art_def,nom|other**1,question';
$syntaxModel[] = 'ver_inf,question';
/* END OF INFINITIF VERBS */

if ($actions[$randomIndex] == 'rep') {
	/* If no verb in the sentence */
	if(empty($wordsByType['ver'])){
		$syntaxModel[] = 'pro_per_con+,aux-1,pro_per*1,lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adj+**1,question';
		$syntaxModel[] = 'pro_per,pro_per_con+,aux*1-1,adj_num|adj_pos|pro_ind,nom|other**1,adj+**1,question';
		$syntaxModel[] = 'pro_per|pro_dem|pro_ind,pro_per_con+,aux*1-2,adv+,ver_past**2,question';
		$syntaxModel[] = 'pro_per_con+,aux,pro_per*1,art_def,art_def+,nom|other**1,adj+**1,question';
		$syntaxModel[] = 'pro_per_con+,aux,pro_per*1,art_ind,nom|other**1,adj+**1,question';
	}
	$syntaxModel[] = 'pro_per|pro_dem|pro_ind,pro_per_con+,ver|aux*1,adv+,question';
	$syntaxModel[] = 'pro_int,ver|aux,pro_per_con+,pro_per*1,question';
	$syntaxModel[] = 'nom|other,question';
	$syntaxModel[] = 'ono,question';
} elseif ($actions[$randomIndex] == 'sug') {
	/* If no verb in the sentence */
	if(empty($wordsByType['ver'])){
		$syntaxModel[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,pro_per_con+,aux*1-2,adv+,other,dot';
		$syntaxModel[] = 'pro_per,pro_per_con+,aux*1-1,adv+,lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adj+**2,dot';
	}
	$syntaxModel[] = 'pro_per,ver|aux*1-1,art_def,art_def+,nom|other**1,adj+**1,dot';
	$syntaxModel[] = 'ver,pro_per_con+,pro_per*1,ver_inf,art_def,art_def+,nom|other**1,adj+**1,dot';
	$syntaxModel[] = 'pro_per,pro_per_con+,ver|aux*1-1,art_ind,nom|other**1,adj+**1,dot';
	$syntaxModel[] = 'pro_per,pro_per_con+,ver|aux*1-1,adv+,art_ind|art_def|adj_num|adj_pos,nom|other**1,adj+**1,dot';
	/* If no verb in the sentence */
	if(empty($wordsByType['ver'])){
		$syntaxModel[] = 'pro_per,pro_per_con+,aux*1-1,art_ind,nom|other**1,adj+**1,dot';
		$syntaxModel[] = 'art_def,art_def+,nom|other**1,pro_per_con+,aux*1-1,adv+,adj+**2,dot';
		$syntaxModel[] = 'adj_num|adj_pos|pro_ind,nom|other**1,pro_per_con+,aux*1-1,adv+,adj+**2,dot';
		$syntaxModel[] = 'pro_per|pro_dem|pro_ind,pro_per_con+,aux*1-1,adv+,adj+,dot';
	}
	$syntaxModel[] = 'pro_per|pro_dem|pro_ind,pro_per_con+,ver|aux*1,dot';
	$syntaxModel[] = 'nom|other,dot';
	$syntaxModel[] = 'ono,dot';
}
