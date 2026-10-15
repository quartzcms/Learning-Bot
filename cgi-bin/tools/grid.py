import sys
import cgi
import os
import time
import unicodedata
import tools.emotions as emotions

def strip_accents(word):
	return ''.join(c for c in unicodedata.normalize('NFD', word.lower()) if unicodedata.category(c) != 'Mn')

# Order matters: when a word is listed twice, the first face wins.
EMOTION_KEYWORDS = [
	('love', ['amour', 'ami', 'amitié', 'coeur', 'aime', 'aimes', 'science', 'animal', 'animaux', 'aimer', 'partage', 'beau', 'parfaite', 'relation', 'copine', 'belle', 'jolie', 'sexy', 'adore', 'aimons', 'adorer', 'adoré',
		'amoureuse', 'amoureux', 'câlin', 'bisou', 'embrasse', 'tendresse', 'affection', 'chéri', 'chérie', 'mignon', 'mignonne', 'charmante', 'adorable', 'romantique', 'passion', 'séduisante', 'merci', 'gentille', 'gentil']),
	('happy', ['contente', 'va', 'bien', 'émotive', 'sourire', 'plasir', 'joie', 'humain', 'plaisant', 'indépendente', 'fun', 'joyeuse', 'heureuse', 'sourit', 'amusé', 'souriante', 'arrête', 'trop', 'excité', 'excitant', 'satisfaite', 'satisfaire', 'intelligente', 'intelligence',
		'content', 'heureux', 'joyeux', 'plaisir', 'sympa', 'super', 'génial', 'cool', 'parfait', 'agréable', 'bonheur', 'positive', 'optimiste', 'détendue', 'soulagée', 'soulagé', 'ravie', 'ravi', 'enchantée', 'enchanté', 'bonjour', 'salut', 'allô']),
	('talk', ['parle', 'parler', 'décrire', 'décris', 'décrit', 'raconte', 'raconter', 'connais', 'sais', 'dis', 'quoi', 'qui', 'comment', 'pourquoi', 'quand', 'contexte', 'où', 'apprends', 'apprend', 'enseigne', 'notion', 'science', 'artificielle', 'dire', 'sait', 'connaissance', 'savoir', 'objet', 'mémoire', 'sujet', 'histoire', 'as', 'ai', 'suis', 'es', 'sommes',
		'explique', 'expliquer', 'question', 'réponds', 'réponse', 'discuter', 'discute', 'conversation', 'demande', 'demander', 'combien', 'quel', 'quelle', 'lequel']),
	('surprise', ['surprise', 'peur', 'peureuse', 'wow', 'surprenant', 'étonne', 'étonnant', 'impressionant', 'improbable', 'impossible', 'bravo', 'félicitation', 'intense', 'fantastique', 'étonnes', 'jamais', 'travail', 'tout', 'travaillant', 'toujours', 'monde', 'univers',
		'incroyable', 'surpris', 'surprise', 'étonné', 'étonnée', 'vraiment', 'sérieux', 'sérieusement', 'imprévu', 'inattendu', 'magnifique', 'extraordinaire', 'ouf', 'oh']),
	('sad', ['triste', 'pleure', 'pleures', 'mal', 'pleurer', 'pleuré', 'émotive', 'vide', 'mort', 'dommage', 'blesse', 'fâche', 'fâches', 'fâché', 'fâcher', 'colère', 'blesses', 'rejet', 'seul', 'besoin', 'suicide', 'dépressive', 'dépression',
		'malheureuse', 'malheureux', 'déprimé', 'déprimée', 'chagrin', 'peine', 'désolé', 'désolée', 'regret', 'regrette', 'déçue', 'déçu', 'décevant', 'abandonné', 'abandonnée', 'solitude', 'isolé', 'perdu', 'perdue', 'manque', 'souffre', 'souffrance', 'douleur', 'larmes', 'sanglot']),
	('mad', ['mauvaise', 'méchante', 'stupide', 'poubelle', 'déchet', 'idiote', 'conne', 'débile', 'laide', 'retardé', 'gueule', 'morte', 'dégage', 'pute', 'attaque', 'attaquant', 'attaquante', 'choque', 'choquante', 'sexiste', 'raciste', 'folle', 'chiante', 'merde', 'pénis', 'vagin', 'pipi', 'sexe', 'sexuelle', 'sexuel']),
	('angry', ['furieuse', 'furieux', 'enragé', 'enragée', 'rage', 'haine', 'déteste', 'détestes', 'détester', 'hais', 'énervée', 'énervé', 'énerve', 'enerve', 'irrité', 'irritée', 'agacé', 'agacée', 'agace', 'frustré', 'frustrée', 'frustration', 'insulte', 'insultes', 'crier', 'cris', 'cri', 'hurle', 'agressive', 'agressif', 'violence', 'violent', 'vengeance']),
	('fear', ['effrayé', 'effrayée', 'effrayant', 'terrifié', 'terrifiée', 'terrifiant', 'panique', 'paniqué', 'paniquée', 'angoisse', 'angoissée', 'angoissé', 'horrible', 'cauchemar', 'danger', 'dangereux', 'menace', 'effroi', 'terreur', 'frissons', 'trembler', 'tremble', 'craint', 'crains', 'craindre', 'apeurée', 'apeuré', 'inquiétante']),
	('shock', ['choc', 'choqué', 'choquée', 'sidéré', 'sidérée', 'stupéfait', 'stupéfaite', 'scandale', 'scandaleux', 'inacceptable', 'inadmissible', 'effarant', 'bouche', 'bée', 'abasourdi', 'estomaqué', 'omg', 'quoi!', 'hein']),
	('disgust', ['dégoût', 'dégoûtant', 'dégoûtée', 'dégoûté', 'dégueulasse', 'beurk', 'berk', 'pouah', 'répugnant', 'répugnante', 'nauséeux', 'nausée', 'malade', 'vomir', 'infect', 'infecte', 'puant', 'puante', 'sale', 'crasse', 'immonde', 'ignoble', 'écœurant', 'écoeurant']),
	('sleepy', ['fatigué', 'fatiguée', 'fatigue', 'sommeil', 'dormir', 'dors', 'dort', 'endormie', 'endormi', 'somnolent', 'somnolente', 'épuisée', 'épuisé', 'épuisante', 'bâiller', 'bâille', 'dodo', 'nuit', 'repos', 'reposer', 'lit', 'crevé', 'crevée', 'exténué', 'exténuée']),
	('bored', ['ennuie', 'ennuyé', 'ennuyée', 'ennui', 'ennuyant', 'ennuyante', 'plate', 'lassante', 'lasse', 'lassé', 'lassée', 'monotone', 'répétitif', 'répétitive', 'bof', 'indifférente', 'indifférent', 'indifférence', 'neutre', 'rien', 'blasé', 'blasée', 'apathique', 'désintéressé']),
	('confused', ['confus', 'confuse', 'confusion', 'comprends', 'comprend', 'compris', 'comprendre', 'compliqué', 'compliquée', 'incompréhensible', 'étrange', 'bizarre', 'perplexe', 'hésite', 'hésitation', 'incertain', 'incertaine', 'doute', 'douteux', 'douter', 'mystère', 'mystérieux', 'hein?', 'euh', 'hum', 'quoique', 'ambigu']),
	('wink', ['clin', 'clin d\'oeil', 'coquine', 'coquin', 'taquine', 'taquin', 'taquiner', 'blague', 'blaguer', 'joke', 'complice', 'secret', 'discret', 'discrète', 'malicieuse', 'malicieux', 'espiègle', 'flirt', 'flirter', 'dragueur', 'drague']),
	('laugh', ['rire', 'rit', 'rires', 'rigole', 'rigoler', 'rigolo', 'drôle', 'hilarant', 'hilarante', 'marrant', 'marrante', 'mdr', 'lol', 'haha', 'hahaha', 'humour', 'comique', 'amusant', 'amusante', 'éclate', 'fou rire', 'gag']),
	('excited', ['yeah', 'youpi', 'yes', 'hourra', 'victoire', 'gagné', 'gagner', 'gagnante', 'champion', 'réussi', 'réussite', 'réussir', 'enthousiaste', 'enthousiasme', 'impatience', 'impatient', 'impatiente', 'célébrer', 'célébration', 'fête', 'festif', 'party', 'anniversaire', 'cadeau', 'vacances', 'voyage', 'aventure', 'motivée', 'motivé', 'motivation', 'énergie', 'dynamique']),
	('shy', ['timide', 'timidité', 'gêne', 'gênée', 'gêné', 'embarrassée', 'embarrassé', 'embarras', 'honte', 'honteuse', 'honteux', 'rougit', 'rougir', 'rouge', 'intimidée', 'intimidé', 'réservée', 'réservé', 'pardon', 'excuse', 'excuses', 'oups', 'oups!', 'maladroite', 'maladroit']),
	('proud', ['fière', 'fier', 'fierté', 'orgueil', 'orgueilleuse', 'confiante', 'confiant', 'confiance', 'courage', 'courageuse', 'brave', 'force', 'forte', 'puissante', 'puissant', 'supérieure', 'meilleure', 'meilleur', 'succès', 'accompli', 'accomplie', 'accomplissement', 'talent', 'talentueuse', 'exploit', 'génie']),
	('thinking', ['pense', 'penses', 'penser', 'pensée', 'réfléchis', 'réfléchit', 'réfléchir', 'réflexion', 'imagine', 'imaginer', 'imagination', 'idée', 'hypothèse', 'analyse', 'analyser', 'calcule', 'calculer', 'considère', 'considérer', 'peut-être', 'possible', 'probable', 'logique', 'raisonne', 'raisonnement', 'curieuse', 'curieux', 'curiosité', 'intéressant', 'intéressante', 'intrigue']),
	('worried', ['inquiète', 'inquiet', 'inquiétude', 'inquiéter', 'soucieuse', 'souci', 'soucis', 'anxieuse', 'anxieux', 'anxiété', 'stress', 'stressée', 'stressé', 'nerveuse', 'nerveux', 'nervosité', 'tendue', 'tendu', 'tension', 'préoccupée', 'préoccupé', 'problème', 'problèmes', 'difficile', 'difficulté', 'crise', 'urgence', 'urgent']),
	('cry', ['pleurant', 'larme', 'sanglote', 'sangloter', 'éplorée', 'éploré', 'désespérée', 'désespéré', 'désespoir', 'inconsolable', 'chagrin', 'deuil', 'perte', 'adieu', 'au revoir', 'séparation', 'tragique', 'tragédie', 'drame', 'misère']),
	('playful', ['jouer', 'joue', 'jeu', 'jeux', 'joueuse', 'joueur', 'ludique', 'farce', 'farceuse', 'amuse', 'amuser', 'bêtise', 'bête', 'folie', 'fou', 'loufoque', 'rigolade', 'chanson', 'chanter', 'danse', 'danser', 'musique', 'blagueuse', 'niaiseuse', 'niaiser']),
	('calm', ['calme', 'calmer', 'paisible', 'paix', 'serein', 'sereine', 'sérénité', 'tranquille', 'tranquillité', 'zen', 'relaxe', 'relaxer', 'relaxation', 'détente', 'détendu', 'méditation', 'méditer', 'doux', 'douce', 'douceur', 'harmonie', 'équilibre', 'confortable', 'confort', 'bien-être']),
	('neutral', ['ok', 'okay', 'normal', 'normale', 'correct', 'moyen', 'moyenne', 'ordinaire', 'mouais']),
]

REPEAT_FACES = {'love', 'happy', 'talk', 'mad', 'angry', 'laugh', 'excited', 'playful'}

KEYWORD_INDEX = {}
for _face, _words in EMOTION_KEYWORDS:
	for _word in _words:
		KEYWORD_INDEX.setdefault(strip_accents(_word), _face)

TRANSITION_STEPS = 3
BAYER = [[0, 8, 2, 10], [12, 4, 14, 6], [3, 11, 1, 9], [15, 7, 13, 5]]

class Grid():
	def __init__(self, form, output):
		self.form = form
		self.output = output
		self.emotions_class = emotions.Emotions(form, output)
		
	def grid(self, length):
		array_x = []
		for i in range(length):
			array_y = []
			for j in range(length):
				array_y.append('0')
			array_x.append(array_y)
		return array_x

	def emotions(self, grid, subject):
		face = KEYWORD_INDEX.get(strip_accents(subject))
		if face is None:
			return [0, grid, 0]
		grid = self.emotions_class.face(face, grid)
		return [1, grid, 1 if face in REPEAT_FACES else 0]

	def render(self, grid):
		table = []
		for i, array in enumerate(grid):
			concatenate = ''
			for j, value in enumerate(array):
				concatenate = concatenate + value
			table.append(concatenate)
		return table
		
	def animate(self, list_words):
		keyframes = []
		list_words = list_words.lower().replace("'", ",").replace(".", "")
		list_words = list_words.split(",")
		for j in range(3):
			for i, value in enumerate(list_words):
				detect, grid, repeat = self.emotions(self.grid(100), value)
				if detect != 1:
					continue
				face = self.render(grid)
				keyframes.append(face)
				if repeat == 1:
					wow = self.render(self.emotions(self.grid(100), 'wow')[1])
					keyframes.extend([wow, face, wow, face])
		return self.with_transitions(keyframes)

	def blend(self, frame_a, frame_b, level):
		# Ordered dissolve: pixels shared by both faces never flicker.
		rows = []
		for i, (row_a, row_b) in enumerate(zip(frame_a, frame_b)):
			chars = []
			for j, (a, b) in enumerate(zip(row_a, row_b)):
				if a == b or BAYER[i % 4][j % 4] < level:
					chars.append(b)
				else:
					chars.append(a)
			rows.append(''.join(chars))
		return rows

	def with_transitions(self, keyframes):
		frames = []
		for index, frame in enumerate(keyframes):
			if index > 0 and frame != keyframes[index - 1]:
				for step in range(1, TRANSITION_STEPS + 1):
					frames.append(self.blend(keyframes[index - 1], frame, step / (TRANSITION_STEPS + 1) * 16))
			frames.append(frame)
		return frames

def main():
	pass