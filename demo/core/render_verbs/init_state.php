<?php
$unusedPronouns = array();
$pronounsToInsert = array();

$verbTypes = array('AUX', 'VER:inf', 'VER:past', 'VER');
$conjugatedVerbTypes = array('VER', 'VER:past', 'AUX');
$determinerTypes = array('ART:def', 'ADJ:pos', 'PRO:pos', 'ADJ:int');
$coordinationWords = array('et', 'ainsi', 'comme');

$selfSuffix = "-m\u{ea}me";
$selfSuffixPlural = "-m\u{ea}mes";

/* Subject pronouns that can be detected around a verb */
$subjectPronounCandidates = array(
	'il', 'elle', 'ils', 'elles', 'j', 'je', 'tu', 'nous', 'vous', 'moi', 'toi', 'soi',
	'soi' . $selfSuffix, 'toi' . $selfSuffix, 'moi' . $selfSuffix, 'nous' . $selfSuffix, 'vous' . $selfSuffix,
	'nous' . $selfSuffixPlural, 'vous' . $selfSuffixPlural, 'lui' . $selfSuffix, 'elle' . $selfSuffix,
	'eux' . $selfSuffixPlural, 'elles' . $selfSuffixPlural,
	'celle', 'ceux', 'celles', 'celui', 'ceci', 'cela', 'on'
);
$subjectPronounCandidatesWithElision = array_merge($subjectPronounCandidates, array('c'));

/* Subject pronoun => grammatical person, number, pronoun to write (null = the same pronoun), gender */
$pronounFormTable = array(
	'il' => array('3s', 's', null, 'm'),
	'elle' => array('3s', 's', null, 'f'),
	'lui' . $selfSuffix => array('3s', 's', null, 'm'),
	'elle' . $selfSuffix => array('3s', 's', null, 'f'),
	'celle' => array('3s', 's', null, 'f'),
	'cela' => array('3s', 's', null, 'm'),
	'ceci' => array('3s', 's', null, 'm'),
	'celui' => array('3s', 's', null, 'm'),
	'soi' => array('3s', 's', null, 'm'),
	'soi' . $selfSuffix => array('3s', 's', null, 'm'),
	'ils' => array('3p', 'p', null, 'm'),
	'elles' => array('3p', 'p', null, 'f'),
	'celles' => array('3p', 'p', null, 'f'),
	'ceux' => array('3p', 'p', null, 'm'),
	'eux' . $selfSuffixPlural => array('3p', 'p', null, 'm'),
	'elles' . $selfSuffixPlural => array('3p', 'p', null, 'f'),
	'j' => array('2s', 's', 'tu', 'm'),
	'je' => array('2s', 's', 'tu', 'm'),
	'tu' => array('1s', 's', 'je', 'm'),
	'nous' => array('2p', 'p', 'vous', 'm'),
	'vous' => array('1p', 'p', 'nous', 'm'),
	'moi' => array('2s', 's', 'toi', 'm'),
	'toi' => array('1s', 's', 'moi', 'm'),
	'toi' . $selfSuffix => array('1s', 's', 'moi' . $selfSuffix, 'm'),
	'moi' . $selfSuffix => array('2s', 's', 'toi' . $selfSuffix, 'm'),
	'nous' . $selfSuffix => array('2p', 'p', 'vous' . $selfSuffix, 'm'),
	'vous' . $selfSuffix => array('1p', 'p', 'nous' . $selfSuffix, 'm'),
	'nous' . $selfSuffixPlural => array('2p', 'p', 'vous' . $selfSuffixPlural, 'm'),
	'vous' . $selfSuffixPlural => array('1p', 'p', 'nous' . $selfSuffixPlural, 'm')
);

/* Personal pronoun => its counterpart when the speaker changes */
$pronounSwapMap = array(
	'j' => 'tu',
	'je' => 'tu',
	'tu' => 'je',
	'nous' => 'vous',
	'vous' => 'nous',
	'moi' => 'toi',
	'toi' => 'moi',
	'soi' => 'moi',
	'toi' . $selfSuffix => 'moi' . $selfSuffix,
	'soi' . $selfSuffix => 'moi' . $selfSuffix,
	'moi' . $selfSuffix => 'toi' . $selfSuffix,
	'nous' . $selfSuffix => 'vous' . $selfSuffix,
	'vous' . $selfSuffix => 'nous' . $selfSuffix,
	'nous' . $selfSuffixPlural => 'vous' . $selfSuffixPlural,
	'vous' . $selfSuffixPlural => 'nous' . $selfSuffixPlural,
	'me' => 'te',
	'te' => 'me'
);
