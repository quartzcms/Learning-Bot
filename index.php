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
		<meta name="rev" content="quartzcms@gmail.com" />
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
									<?php include "demo/sessions/save_sessions.php"; ?>
									<?php include "demo/sessions/use_sessions.php"; ?>
									<?php if(use_session('captcha') != '15'){ ?>
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
									<div class="row">
										<form action="demo/language/language.php" method="post">
											<div class="col-md-6">
												<p>
													<select name="language" class="form-control">
														<option value="fr">- Select Language -</option>
														<option value="af" <?php if(use_session('language') && use_session('language') =='af'){ echo 'selected="selected"'; } ?>>Afrikaans</option>
														<option value="sq" <?php if(use_session('language') && use_session('language') =='sq'){ echo 'selected="selected"'; } ?>>Albanian</option>
														<option value="am" <?php if(use_session('language') && use_session('language') =='am'){ echo 'selected="selected"'; } ?>>Amharic</option>
														<option value="ar" <?php if(use_session('language') && use_session('language') =='ar'){ echo 'selected="selected"'; } ?>>Arabic</option>
														<option value="hy" <?php if(use_session('language') && use_session('language') =='hy'){ echo 'selected="selected"'; } ?>>Armenian</option>
														<option value="az" <?php if(use_session('language') && use_session('language') =='az'){ echo 'selected="selected"'; } ?>>Azeerbaijani</option>
														<option value="eu" <?php if(use_session('language') && use_session('language') =='eu'){ echo 'selected="selected"'; } ?>>Basque</option>
														<option value="be" <?php if(use_session('language') && use_session('language') =='be'){ echo 'selected="selected"'; } ?>>Belarusian</option>
														<option value="bn" <?php if(use_session('language') && use_session('language') =='bn'){ echo 'selected="selected"'; } ?>>Bengali</option>
														<option value="bs" <?php if(use_session('language') && use_session('language') =='bs'){ echo 'selected="selected"'; } ?>>Bosnian</option>
														<option value="bg" <?php if(use_session('language') && use_session('language') =='bg'){ echo 'selected="selected"'; } ?>>Bulgarian</option>
														<option value="ca" <?php if(use_session('language') && use_session('language') =='ca'){ echo 'selected="selected"'; } ?>>Catalan</option>
														<option value="ceb" <?php if(use_session('language') && use_session('language') =='ceb'){ echo 'selected="selected"'; } ?>>Cebuano</option>
														<option value="zh-CN" <?php if(use_session('language') && use_session('language') =='zh-CN'){ echo 'selected="selected"'; } ?>>Chinese (Simplified)</option>
														<option value="zh-TW" <?php if(use_session('language') && use_session('language') =='zh-TW'){ echo 'selected="selected"'; } ?>>Chinese (Traditional)</option>
														<option value="co" <?php if(use_session('language') && use_session('language') =='co'){ echo 'selected="selected"'; } ?>>Corsican</option>
														<option value="hr" <?php if(use_session('language') && use_session('language') =='hr'){ echo 'selected="selected"'; } ?>>Croatian</option>
														<option value="cs" <?php if(use_session('language') && use_session('language') =='cs'){ echo 'selected="selected"'; } ?>>Czech</option>
														<option value="da" <?php if(use_session('language') && use_session('language') =='da'){ echo 'selected="selected"'; } ?>>Danish</option>
														<option value="nl" <?php if(use_session('language') && use_session('language') =='nl'){ echo 'selected="selected"'; } ?>>Dutch</option>
														<option value="en" <?php if(use_session('language') && use_session('language') =='en'){ echo 'selected="selected"'; } ?>>English</option>
														<option value="eo" <?php if(use_session('language') && use_session('language') =='eo'){ echo 'selected="selected"'; } ?>>Esperanto</option>
														<option value="et" <?php if(use_session('language') && use_session('language') =='et'){ echo 'selected="selected"'; } ?>>Estonian</option>
														<option value="fi" <?php if(use_session('language') && use_session('language') =='fi'){ echo 'selected="selected"'; } ?>>Finnish</option>
														<option value="fr" <?php if(use_session('language') && use_session('language') =='fr'){ echo 'selected="selected"'; } ?>>French</option>
														<option value="fy" <?php if(use_session('language') && use_session('language') =='fy'){ echo 'selected="selected"'; } ?>>Frisian</option>
														<option value="gl" <?php if(use_session('language') && use_session('language') =='gl'){ echo 'selected="selected"'; } ?>>Galician</option>
														<option value="ka" <?php if(use_session('language') && use_session('language') =='ka'){ echo 'selected="selected"'; } ?>>Georgian</option>
														<option value="de" <?php if(use_session('language') && use_session('language') =='de'){ echo 'selected="selected"'; } ?>>German</option>
														<option value="el" <?php if(use_session('language') && use_session('language') =='el'){ echo 'selected="selected"'; } ?>>Greek</option>
														<option value="gu" <?php if(use_session('language') && use_session('language') =='gu'){ echo 'selected="selected"'; } ?>>Gujarati</option>
														<option value="ht" <?php if(use_session('language') && use_session('language') =='ht'){ echo 'selected="selected"'; } ?>>Haitian Creole</option>
														<option value="ha" <?php if(use_session('language') && use_session('language') =='ha'){ echo 'selected="selected"'; } ?>>Hausa</option>
														<option value="haw" <?php if(use_session('language') && use_session('language') =='haw'){ echo 'selected="selected"'; } ?>>Hawaiian</option>
														<option value="iw" <?php if(use_session('language') && use_session('language') =='iw'){ echo 'selected="selected"'; } ?>>Hebrew</option>
														<option value="hi" <?php if(use_session('language') && use_session('language') =='hi'){ echo 'selected="selected"'; } ?>>Hindi</option>
														<option value="hmn" <?php if(use_session('language') && use_session('language') =='hmn'){ echo 'selected="selected"'; } ?>>Hmong</option>
														<option value="hu" <?php if(use_session('language') && use_session('language') =='hu'){ echo 'selected="selected"'; } ?>>Hungarian</option>
														<option value="is" <?php if(use_session('language') && use_session('language') =='is'){ echo 'selected="selected"'; } ?>>Icelandic</option>
														<option value="ig" <?php if(use_session('language') && use_session('language') =='ig'){ echo 'selected="selected"'; } ?>>Igbo</option>
														<option value="id" <?php if(use_session('language') && use_session('language') =='id'){ echo 'selected="selected"'; } ?>>Indonesian</option>
														<option value="ga" <?php if(use_session('language') && use_session('language') =='ga'){ echo 'selected="selected"'; } ?>>Irish</option>
														<option value="it" <?php if(use_session('language') && use_session('language') =='it'){ echo 'selected="selected"'; } ?>>Italian</option>
														<option value="ja" <?php if(use_session('language') && use_session('language') =='ja'){ echo 'selected="selected"'; } ?>>Japanese</option>
														<option value="jw" <?php if(use_session('language') && use_session('language') =='jw'){ echo 'selected="selected"'; } ?>>Javanese</option>
														<option value="kn" <?php if(use_session('language') && use_session('language') =='kn'){ echo 'selected="selected"'; } ?>>Kannada</option>
														<option value="kk" <?php if(use_session('language') && use_session('language') =='kk'){ echo 'selected="selected"'; } ?>>Kazakh</option>
														<option value="km" <?php if(use_session('language') && use_session('language') =='km'){ echo 'selected="selected"'; } ?>>Khmer</option>
														<option value="ko" <?php if(use_session('language') && use_session('language') =='ko'){ echo 'selected="selected"'; } ?>>Korean</option>
														<option value="ku" <?php if(use_session('language') && use_session('language') =='ku'){ echo 'selected="selected"'; } ?>>Kurdish</option>
														<option value="ky" <?php if(use_session('language') && use_session('language') =='ky'){ echo 'selected="selected"'; } ?>>Kyrgyz</option>
														<option value="lo" <?php if(use_session('language') && use_session('language') =='lo'){ echo 'selected="selected"'; } ?>>Lao</option>
														<option value="la" <?php if(use_session('language') && use_session('language') =='la'){ echo 'selected="selected"'; } ?>>Latin</option>
														<option value="lv" <?php if(use_session('language') && use_session('language') =='lv'){ echo 'selected="selected"'; } ?>>Latvian</option>
														<option value="lt" <?php if(use_session('language') && use_session('language') =='lt'){ echo 'selected="selected"'; } ?>>Lithuanian</option>
														<option value="lb" <?php if(use_session('language') && use_session('language') =='lb'){ echo 'selected="selected"'; } ?>>Luxembourgish</option>
														<option value="mk" <?php if(use_session('language') && use_session('language') =='mk'){ echo 'selected="selected"'; } ?>>Macedonian</option>
														<option value="mg" <?php if(use_session('language') && use_session('language') =='mg'){ echo 'selected="selected"'; } ?>>Malagasy</option>
														<option value="ms" <?php if(use_session('language') && use_session('language') =='ms'){ echo 'selected="selected"'; } ?>>Malay</option>
														<option value="ml" <?php if(use_session('language') && use_session('language') =='ml'){ echo 'selected="selected"'; } ?>>Malayalam</option>
														<option value="mi" <?php if(use_session('language') && use_session('language') =='mi'){ echo 'selected="selected"'; } ?>>Maori</option>
														<option value="mr" <?php if(use_session('language') && use_session('language') =='mr'){ echo 'selected="selected"'; } ?>>Marathi</option>
														<option value="mn" <?php if(use_session('language') && use_session('language') =='mn'){ echo 'selected="selected"'; } ?>>Mongolian</option>
														<option value="my" <?php if(use_session('language') && use_session('language') =='my'){ echo 'selected="selected"'; } ?>>Myanmar (Burmese)</option>
														<option value="ne" <?php if(use_session('language') && use_session('language') =='ne'){ echo 'selected="selected"'; } ?>>Nepali</option>
														<option value="no" <?php if(use_session('language') && use_session('language') =='no'){ echo 'selected="selected"'; } ?>>Norwegian</option>
														<option value="ny" <?php if(use_session('language') && use_session('language') =='ny'){ echo 'selected="selected"'; } ?>>Nyanja (Chichewa)</option>
														<option value="ps" <?php if(use_session('language') && use_session('language') =='ps'){ echo 'selected="selected"'; } ?>>Pashto</option>
														<option value="fa" <?php if(use_session('language') && use_session('language') =='fa'){ echo 'selected="selected"'; } ?>>Persian</option>
														<option value="pl" <?php if(use_session('language') && use_session('language') =='pl'){ echo 'selected="selected"'; } ?>>Polish</option>
														<option value="pt" <?php if(use_session('language') && use_session('language') =='pt'){ echo 'selected="selected"'; } ?>>Portuguese</option>
														<option value="ma" <?php if(use_session('language') && use_session('language') =='ma'){ echo 'selected="selected"'; } ?>>Punjabi</option>
														<option value="ro" <?php if(use_session('language') && use_session('language') =='ro'){ echo 'selected="selected"'; } ?>>Romanian</option>
														<option value="ru" <?php if(use_session('language') && use_session('language') =='ru'){ echo 'selected="selected"'; } ?>>Russian</option>
														<option value="sm" <?php if(use_session('language') && use_session('language') =='sm'){ echo 'selected="selected"'; } ?>>Samoan</option>
														<option value="gd" <?php if(use_session('language') && use_session('language') =='gd'){ echo 'selected="selected"'; } ?>>Scots Gaelic</option>
														<option value="sr" <?php if(use_session('language') && use_session('language') =='sr'){ echo 'selected="selected"'; } ?>>Serbian</option>
														<option value="st" <?php if(use_session('language') && use_session('language') =='st'){ echo 'selected="selected"'; } ?>>Sesotho</option>
														<option value="sn" <?php if(use_session('language') && use_session('language') =='sn'){ echo 'selected="selected"'; } ?>>Shona</option>
														<option value="sd" <?php if(use_session('language') && use_session('language') =='sd'){ echo 'selected="selected"'; } ?>>Sindhi</option>
														<option value="si" <?php if(use_session('language') && use_session('language') =='si'){ echo 'selected="selected"'; } ?>>Sinhala (Sinhalese)</option>
														<option value="sk" <?php if(use_session('language') && use_session('language') =='sk'){ echo 'selected="selected"'; } ?>>Slovak</option>
														<option value="sl" <?php if(use_session('language') && use_session('language') =='sl'){ echo 'selected="selected"'; } ?>>Slovenian</option>
														<option value="so" <?php if(use_session('language') && use_session('language') =='so'){ echo 'selected="selected"'; } ?>>Somali</option>
														<option value="es" <?php if(use_session('language') && use_session('language') =='es'){ echo 'selected="selected"'; } ?>>Spanish</option>
														<option value="su" <?php if(use_session('language') && use_session('language') =='su'){ echo 'selected="selected"'; } ?>>Sundanese</option>
														<option value="sw" <?php if(use_session('language') && use_session('language') =='sw'){ echo 'selected="selected"'; } ?>>Swahili</option>
														<option value="sv" <?php if(use_session('language') && use_session('language') =='sv'){ echo 'selected="selected"'; } ?>>Swedish</option>
														<option value="tl" <?php if(use_session('language') && use_session('language') =='tl'){ echo 'selected="selected"'; } ?>>Tagalog (Filipino)</option>
														<option value="tg" <?php if(use_session('language') && use_session('language') =='tg'){ echo 'selected="selected"'; } ?>>Tajik</option>
														<option value="ta" <?php if(use_session('language') && use_session('language') =='ta'){ echo 'selected="selected"'; } ?>>Tamil</option>
														<option value="te" <?php if(use_session('language') && use_session('language') =='te'){ echo 'selected="selected"'; } ?>>Telugu</option>
														<option value="th" <?php if(use_session('language') && use_session('language') =='th'){ echo 'selected="selected"'; } ?>>Thai</option>
														<option value="tr" <?php if(use_session('language') && use_session('language') =='tr'){ echo 'selected="selected"'; } ?>>Turkish</option>
														<option value="uk" <?php if(use_session('language') && use_session('language') =='uk'){ echo 'selected="selected"'; } ?>>Ukrainian</option>
														<option value="ur" <?php if(use_session('language') && use_session('language') =='ur'){ echo 'selected="selected"'; } ?>>Urdu</option>
														<option value="uz" <?php if(use_session('language') && use_session('language') =='uz'){ echo 'selected="selected"'; } ?>>Uzbek</option>
														<option value="vi" <?php if(use_session('language') && use_session('language') =='vi'){ echo 'selected="selected"'; } ?>>Vietnamese</option>
														<option value="cy" <?php if(use_session('language') && use_session('language') =='cy'){ echo 'selected="selected"'; } ?>>Welsh</option>
														<option value="xh" <?php if(use_session('language') && use_session('language') =='xh'){ echo 'selected="selected"'; } ?>>Xhosa</option>
														<option value="yi" <?php if(use_session('language') && use_session('language') =='yi'){ echo 'selected="selected"'; } ?>>Yiddish</option>
														<option value="yo" <?php if(use_session('language') && use_session('language') =='yo'){ echo 'selected="selected"'; } ?>>Yoruba</option>
														<option value="zu" <?php if(use_session('language') && use_session('language') =='zu'){ echo 'selected="selected"'; } ?>>Zulu</option>
													</select>
												</p>
											</div>
											<div class="col-md-2">
												<p><button class="btn btn-info" style="width: 100%;" name="submit" type="submit">Change Language</button></p>
											</div>
										</form>
										<form action="/demo/channels/channels.php" method="post">
											<div class="col-md-2">
												<p>
													<select name="channels" class="form-control">
														<option value="255.255.255.255" <?php if(use_session('channels') && (use_session('channels') =='255.255.255.255' || use_session('channels') =='')){ echo 'selected="selected"'; } ?>>- Default -</option>
														<option value="sexy" <?php if(use_session('channels') && use_session('channels') =='sexy'){ echo 'selected="selected"'; } ?>>For Adults</option>
														<option value="violent" <?php if(use_session('channels') && use_session('channels') =='violent'){ echo 'selected="selected"'; } ?>>Violent</option>
														<option value="lost" <?php if(use_session('channels') && use_session('channels') =='lost'){ echo 'selected="selected"'; } ?>>Lost</option>
													</select>
												</p>
											</div>
											<div class="col-md-2">
												<p>
													<button class="btn btn-info" style="width: 100%;" name="submit" type="submit">Change Channel</button>
												</p>
											</div>
										</form>
									</div>
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
										var o = 0;
										var load = function($this, o){
											setTimeout(function(){
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
															$(".response").prepend("<p>Julie: <span>" + json.response +"</span></p>");
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
														
														<?php
															if(use_session('language') && use_session('language') == 'en'){
														?>
															responsiveVoice.speak(json.response, 'US English Female');
														<?php
															}
														?>
														
														<?php
															if((use_session('language') && use_session('language') == 'fr') || !use_session('language')){
														?>
															responsiveVoice.speak(json.response, 'French Female');
														<?php
															} 
														?>
													}
												}).done(function() {
													o++;
													if(o == 2){
														$("#question").css('display', 'block');
														$("#submit").css('display', 'inline-block');
														$('#loading').css('display', 'none');
														
														$('#question').focus();
														$('#question').val('');
													}
													if(o < 2){
														load($this, o);
													}	
												});
											}, 50);
										}
										
										$('#ask').on('submit', function(event) {
											var $this = $(this);
											if($('#question').val() != '') {
												$("#question").css('display', 'none');
												$("#submit").css('display', 'none');
												$('#loading').css('display', 'block');

												$(".response").prepend("<p>Me: " + $('#question').val() + "</p>");
												load($this, 0);
											}
										});
									});	
								</script>
								<?php 
									write_session('learn', '');
									write_session('table', '');
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