<?php session_start(); ?>
<!DOCTYPE html>
<html>
	<head>
    	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <meta name="content-type" content="utf-8" />
        <meta name="content-language" content="en" />
        <meta name="revisit-after" content="7 days">
        <meta name="description" content="A free french chatbot that understand sentences and learn from humans." />
        <meta name="keywords" content="conversation, french, free, computer, artificial, machine, bot, chatbot, Julie" />
        <meta name="robots" content="index, follow" />
		<meta name="author" content="Alexandre Lefebvre" />
		<meta name="rev" content="alefebvre2400@gmail.com" />
		<title>Learning Bot - Julie</title>
        <link rel="stylesheet" type="text/css" href="templates/default/bootstrap/custom/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="templates/default/style/custom.css" />
        <link rel="stylesheet" type="text/css" href="templates/default/style/edits/custom.css" />
   		<link rel="stylesheet" type="text/css" href="templates/default/style/screen.css" />
        <script type="text/javascript" src="templates/default/js/jquery-1.11.1.min.js"></script>
		<script type="text/javascript" src="templates/default/bootstrap/js/bootstrap.min.js"></script>
        <script src='//code.responsivevoice.org/responsivevoice.js'></script>
	</head>
	<body>
        <section>
            <div class="container-fluid" style="margin-top: 15px;">
				<div class="row">
					<div class="custom">
						<div id='content-item'>
							<div class="col-md-12">
								<div class="alert alert-warning">
									<p>All the bots on this website are coded for French.</p>
									<p>To use this Chatbot please first enter the captcha code and send.</p>
									<?php if($_SESSION['captcha'] != '15'){ ?>
										<form action="demo/captcha/captcha.php" method="post">
											<div class="row">
												<div class="col-md-10">
													<p><input type="text" class="form-control" name="captcha" placeholder="* Anti-bots: (six plus ten)(-1)" /></p>
												</div>
												<div class="col-md-2">
													<p><button type="submit" style="width: 100%;" name="submit" class="btn btn-success">Send</button></p>
												</div>
											</div>
										</form>
									<?php } ?>
									<form action="demo/language/language.php" method="post">
										<div class="row">
											<div class="col-md-10">
												<p>
													<select name="language" class="form-control">
														<option value="fr">- Select Language -</option>
														<option value="af" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='af'){ echo 'selected="selected"'; } ?>>Afrikaans</option>
														<option value="sq" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='sq'){ echo 'selected="selected"'; } ?>>Albanian</option>
														<option value="am" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='am'){ echo 'selected="selected"'; } ?>>Amharic</option>
														<option value="ar" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='ar'){ echo 'selected="selected"'; } ?>>Arabic</option>
														<option value="hy" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='hy'){ echo 'selected="selected"'; } ?>>Armenian</option>
														<option value="az" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='az'){ echo 'selected="selected"'; } ?>>Azeerbaijani</option>
														<option value="eu" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='eu'){ echo 'selected="selected"'; } ?>>Basque</option>
														<option value="be" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='be'){ echo 'selected="selected"'; } ?>>Belarusian</option>
														<option value="bn" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='bn'){ echo 'selected="selected"'; } ?>>Bengali</option>
														<option value="bs" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='bs'){ echo 'selected="selected"'; } ?>>Bosnian</option>
														<option value="bg" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='bg'){ echo 'selected="selected"'; } ?>>Bulgarian</option>
														<option value="ca" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='ca'){ echo 'selected="selected"'; } ?>>Catalan</option>
														<option value="ceb" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='ceb'){ echo 'selected="selected"'; } ?>>Cebuano</option>
														<option value="zh-CN" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='zh-CN'){ echo 'selected="selected"'; } ?>>Chinese (Simplified)</option>
														<option value="zh-TW" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='zh-TW'){ echo 'selected="selected"'; } ?>>Chinese (Traditional)</option>
														<option value="co" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='co'){ echo 'selected="selected"'; } ?>>Corsican</option>
														<option value="hr" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='hr'){ echo 'selected="selected"'; } ?>>Croatian</option>
														<option value="cs" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='cs'){ echo 'selected="selected"'; } ?>>Czech</option>
														<option value="da" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='da'){ echo 'selected="selected"'; } ?>>Danish</option>
														<option value="nl" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='nl'){ echo 'selected="selected"'; } ?>>Dutch</option>
														<option value="en" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='en'){ echo 'selected="selected"'; } ?>>English</option>
														<option value="eo" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='eo'){ echo 'selected="selected"'; } ?>>Esperanto</option>
														<option value="et" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='et'){ echo 'selected="selected"'; } ?>>Estonian</option>
														<option value="fi" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='fi'){ echo 'selected="selected"'; } ?>>Finnish</option>
														<option value="fr" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='fr'){ echo 'selected="selected"'; } ?>>French</option>
														<option value="fy" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='fy'){ echo 'selected="selected"'; } ?>>Frisian</option>
														<option value="gl" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='gl'){ echo 'selected="selected"'; } ?>>Galician</option>
														<option value="ka" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='ka'){ echo 'selected="selected"'; } ?>>Georgian</option>
														<option value="de" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='de'){ echo 'selected="selected"'; } ?>>German</option>
														<option value="el" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='el'){ echo 'selected="selected"'; } ?>>Greek</option>
														<option value="gu" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='gu'){ echo 'selected="selected"'; } ?>>Gujarati</option>
														<option value="ht" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='ht'){ echo 'selected="selected"'; } ?>>Haitian Creole</option>
														<option value="ha" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='ha'){ echo 'selected="selected"'; } ?>>Hausa</option>
														<option value="haw" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='haw'){ echo 'selected="selected"'; } ?>>Hawaiian</option>
														<option value="iw" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='iw'){ echo 'selected="selected"'; } ?>>Hebrew</option>
														<option value="hi" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='hi'){ echo 'selected="selected"'; } ?>>Hindi</option>
														<option value="hmn" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='hmn'){ echo 'selected="selected"'; } ?>>Hmong</option>
														<option value="hu" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='hu'){ echo 'selected="selected"'; } ?>>Hungarian</option>
														<option value="is" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='is'){ echo 'selected="selected"'; } ?>>Icelandic</option>
														<option value="ig" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='ig'){ echo 'selected="selected"'; } ?>>Igbo</option>
														<option value="id" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='id'){ echo 'selected="selected"'; } ?>>Indonesian</option>
														<option value="ga" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='ga'){ echo 'selected="selected"'; } ?>>Irish</option>
														<option value="it" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='it'){ echo 'selected="selected"'; } ?>>Italian</option>
														<option value="ja" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='ja'){ echo 'selected="selected"'; } ?>>Japanese</option>
														<option value="jw" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='jw'){ echo 'selected="selected"'; } ?>>Javanese</option>
														<option value="kn" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='kn'){ echo 'selected="selected"'; } ?>>Kannada</option>
														<option value="kk" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='kk'){ echo 'selected="selected"'; } ?>>Kazakh</option>
														<option value="km" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='km'){ echo 'selected="selected"'; } ?>>Khmer</option>
														<option value="ko" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='ko'){ echo 'selected="selected"'; } ?>>Korean</option>
														<option value="ku" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='ku'){ echo 'selected="selected"'; } ?>>Kurdish</option>
														<option value="ky" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='ky'){ echo 'selected="selected"'; } ?>>Kyrgyz</option>
														<option value="lo" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='lo'){ echo 'selected="selected"'; } ?>>Lao</option>
														<option value="la" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='la'){ echo 'selected="selected"'; } ?>>Latin</option>
														<option value="lv" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='lv'){ echo 'selected="selected"'; } ?>>Latvian</option>
														<option value="lt" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='lt'){ echo 'selected="selected"'; } ?>>Lithuanian</option>
														<option value="lb" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='lb'){ echo 'selected="selected"'; } ?>>Luxembourgish</option>
														<option value="mk" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='mk'){ echo 'selected="selected"'; } ?>>Macedonian</option>
														<option value="mg" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='mg'){ echo 'selected="selected"'; } ?>>Malagasy</option>
														<option value="ms" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='ms'){ echo 'selected="selected"'; } ?>>Malay</option>
														<option value="ml" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='ml'){ echo 'selected="selected"'; } ?>>Malayalam</option>
														<option value="mi" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='mi'){ echo 'selected="selected"'; } ?>>Maori</option>
														<option value="mr" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='mr'){ echo 'selected="selected"'; } ?>>Marathi</option>
														<option value="mn" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='mn'){ echo 'selected="selected"'; } ?>>Mongolian</option>
														<option value="my" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='my'){ echo 'selected="selected"'; } ?>>Myanmar (Burmese)</option>
														<option value="ne" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='ne'){ echo 'selected="selected"'; } ?>>Nepali</option>
														<option value="no" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='no'){ echo 'selected="selected"'; } ?>>Norwegian</option>
														<option value="ny" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='ny'){ echo 'selected="selected"'; } ?>>Nyanja (Chichewa)</option>
														<option value="ps" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='ps'){ echo 'selected="selected"'; } ?>>Pashto</option>
														<option value="fa" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='fa'){ echo 'selected="selected"'; } ?>>Persian</option>
														<option value="pl" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='pl'){ echo 'selected="selected"'; } ?>>Polish</option>
														<option value="pt" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='pt'){ echo 'selected="selected"'; } ?>>Portuguese</option>
														<option value="ma" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='ma'){ echo 'selected="selected"'; } ?>>Punjabi</option>
														<option value="ro" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='ro'){ echo 'selected="selected"'; } ?>>Romanian</option>
														<option value="ru" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='ru'){ echo 'selected="selected"'; } ?>>Russian</option>
														<option value="sm" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='sm'){ echo 'selected="selected"'; } ?>>Samoan</option>
														<option value="gd" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='gd'){ echo 'selected="selected"'; } ?>>Scots Gaelic</option>
														<option value="sr" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='sr'){ echo 'selected="selected"'; } ?>>Serbian</option>
														<option value="st" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='st'){ echo 'selected="selected"'; } ?>>Sesotho</option>
														<option value="sn" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='sn'){ echo 'selected="selected"'; } ?>>Shona</option>
														<option value="sd" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='sd'){ echo 'selected="selected"'; } ?>>Sindhi</option>
														<option value="si" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='si'){ echo 'selected="selected"'; } ?>>Sinhala (Sinhalese)</option>
														<option value="sk" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='sk'){ echo 'selected="selected"'; } ?>>Slovak</option>
														<option value="sl" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='sl'){ echo 'selected="selected"'; } ?>>Slovenian</option>
														<option value="so" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='so'){ echo 'selected="selected"'; } ?>>Somali</option>
														<option value="es" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='es'){ echo 'selected="selected"'; } ?>>Spanish</option>
														<option value="su" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='su'){ echo 'selected="selected"'; } ?>>Sundanese</option>
														<option value="sw" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='sw'){ echo 'selected="selected"'; } ?>>Swahili</option>
														<option value="sv" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='sv'){ echo 'selected="selected"'; } ?>>Swedish</option>
														<option value="tl" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='tl'){ echo 'selected="selected"'; } ?>>Tagalog (Filipino)</option>
														<option value="tg" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='tg'){ echo 'selected="selected"'; } ?>>Tajik</option>
														<option value="ta" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='ta'){ echo 'selected="selected"'; } ?>>Tamil</option>
														<option value="te" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='te'){ echo 'selected="selected"'; } ?>>Telugu</option>
														<option value="th" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='th'){ echo 'selected="selected"'; } ?>>Thai</option>
														<option value="tr" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='tr'){ echo 'selected="selected"'; } ?>>Turkish</option>
														<option value="uk" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='uk'){ echo 'selected="selected"'; } ?>>Ukrainian</option>
														<option value="ur" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='ur'){ echo 'selected="selected"'; } ?>>Urdu</option>
														<option value="uz" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='uz'){ echo 'selected="selected"'; } ?>>Uzbek</option>
														<option value="vi" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='vi'){ echo 'selected="selected"'; } ?>>Vietnamese</option>
														<option value="cy" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='cy'){ echo 'selected="selected"'; } ?>>Welsh</option>
														<option value="xh" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='xh'){ echo 'selected="selected"'; } ?>>Xhosa</option>
														<option value="yi" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='yi'){ echo 'selected="selected"'; } ?>>Yiddish</option>
														<option value="yo" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='yo'){ echo 'selected="selected"'; } ?>>Yoruba</option>
														<option value="zu" <?php if(isset($_SESSION['language']) && $_SESSION['language'] =='zu'){ echo 'selected="selected"'; } ?>>Zulu</option>
													</select>
												</p>
											</div>
											<div class="col-md-2">
												<p><button class="btn btn-info" style="width: 100%;" name="submit" type="submit">Change language</button></p>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
					<div class="custom">
						<div id='content-item'>
							<div class="col-md-12">
								<script type="text/javascript">
									$(document).ready(function() {
										$(".predefined").click(function() {
											$("#question").val($(this).text()); 
										});
										
										$('#ask').on('submit', function(event) {
											var $this = $(this);
											
											if($('#question').val() != '') {
												$("#question").css('display', 'none');
												$("#submit").css('display', 'none');
												$('#loading').css('display', 'block');

												$(".response").prepend("<p>Me: " + $('#question').val() + "</p>");
												
												$.ajax({
													url: $this.attr('action'),
													type: $this.attr('method'),
													data: $this.serialize(),
													dataType: 'json',
													success: function(json) {
														$('.pattern-chosen .answer').html('');
														$('.words-found .answer').html('');
														$('.if-already-said .answer').html('');
														$('.learning-next-sentence .answer').html('');
													
														if(json.response != '') {
															$(".response").prepend("<p>Julie: <span>" + json.response + "</span></p>");
														}
														
														if(json.analyse != '') {
															console.log(json.analyse);
															
															$('.pattern-chosen .answer').html(json.analyse.pattern_chosen);
															$.each(json.analyse.words_found, function(index, value){
																$.each(value, function(index2, value2){
																	$('.words-found .answer').append('<li style="float:left; width:31%; padding: 0% 2% 2% 0%;">Type: ' + index + '<ul style="padding: 0px; margin: 0px; list-style-type: none;"><li>Word: ' + value2['ortho'] + '</li><li>Verb group: ' + value2['lemme'] + '</li><li>Kind: ' + value2['genre'] + '</li><li>Number: ' + value2['nombre'] + '</li><li>Verb extra: ' + value2['infover'] + '</li></ul></li>');
																});
															});
															$('.if-already-said .answer').html(json.analyse.already_said);
															$('.learning-next-sentence .answer').html(json.analyse.will_learn);
														}
														
														$('.box-response').height($('.box-extra').height());
														$('.response').height($('.box-response').height());
														
														if(json.query != '') {
															console.log(json.query);
														}
														$("#question").css('display', 'block');
														$("#submit").css('display', 'inline-block');
														$('#loading').css('display', 'none');
														
														<?php
															if(isset($_SESSION['language']) && $_SESSION['language'] == 'en'){
														?>
															responsiveVoice.speak(json.response, 'US English Female');
														<?php
															}
														?>
														
														<?php
															if((isset($_SESSION['language']) && $_SESSION['language'] == 'fr') || !isset($_SESSION['language'])){
														?>
															responsiveVoice.speak(json.response, 'French Female');
														<?php
															} 
														?>
													}
												});
												
												$('#question').val('');
												$('#question').focus();
											}
										});
									});	
								</script>
								<?php 
									$_SESSION['learn'] = '';
									$_SESSION['table'] = '';
								?>
								<div class="row">
									<div class="col-md-8">
										<h3 style="margin-top: 0px;"><span class="glyphicon glyphicon-user"></span>Julie</h3>
										<div class="well box-response">
											<div class="response" style="width: 100%; height: 350px; overflow-y: scroll;"></div>
										</div>
									</div>
									<div class="col-md-4">
										<h3 style="margin-top: 0px;"><span class="glyphicon glyphicon-tags"></span>Extra</h3>
										<div class="well box-extra">
											<div class="text-success pattern-chosen" style="clear:both;">
												<h4>Pattern chosen</h4>
												<p class="answer"></p>
											</div>
											<div class="text-warning words-found" style="clear:both;">
												<h4>Words found</h4>
												<ul class="answer" style="list-style-type: none; padding: 0px; margin: 0px; "></ul>
											</div>
											<div class="text-info if-already-said" style="clear:both;">
												<h4>If already said</h4>
												<p class="answer"></p>
											</div>
											<div class="text-danger learning-next-sentence" style="clear:both;">
												<h4>Will learn reply</h4>
												<p class="answer"></p>
											</div>
										</div>
									</div>
								</div>
								<div class="well" style="min-height:74px;">
									<form class="ask" id="ask" action="demo/keywords/ai.php" method="post" onsubmit="return false;" enctype="multipart/form-data">
										<div class="row">
											<div class="col-md-8">                                
												<input type="text" class="form-control" id="question" name="question" placeholder="Ask the chatbot">
											</div>
											<div class="col-md-4">
												<button class="btn btn-success record" type="button" style="width: 33.333%;">Record</button><button class="btn btn-warning stop" type="button" style="width: 33.333%;">Stop</button><button type="submit" class="btn btn-danger" style="width: 33.333%;" id="submit">Send</button>
											</div>
										</div>
										<p id="loading" style="display:none;">Loading ...</p>
										<input value="one" name="type" class="type" type="hidden" />
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<script src="demo/web-dictaphone/custom.js"></script>
	</body>
</html>