import sys
import cgi
import os
import time
import tools.emotions as emotions

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
		detect = 0
		repeat = 0
		if subject in ['amour', 'ami', 'amitié', 'coeur', 'aime', 'aimes', 'science', 'animal', 'animaux', 'aimer', 'partage', 'beau', 'parfaite', 'relation', 'copine', 'belle', 'jolie', 'sexy', 'adore', 'aimons', 'adorer', 'adoré']:
			grid = self.emotions_class.love_face(grid)
			detect = 1
			repeat = 1
		elif subject in ['contente', 'va', 'bien', 'émotive', 'sourire', 'plasir', 'joie', 'humain', 'plaisant', 'indépendente', 'fun', 'joyeuse', 'heureuse', 'sourit', 'amusé', 'souriante', 'arrête', 'trop', 'excité', 'excitant', 'satisfaite', 'satisfaire', 'intelligente', 'intelligence']:
			grid = self.emotions_class.happy_face(grid)
			detect = 1
			repeat = 1
		elif subject in ['parle', 'parler', 'décrire', 'décris', 'décrit', 'raconte', 'raconter', 'connais', 'sais', 'dis', 'quoi', 'qui', 'comment', 'pourquoi', 'quand', 'contexte', 'où', 'apprends', 'apprend', 'enseigne', 'notion', 'science', 'artificielle', 'dire', 'sait', 'connaissance', 'savoir', 'objet', 'mémoire', 'sujet', 'histoire', 'as', 'ai', 'suis', 'es', 'sommes']:
			grid = self.emotions_class.talk_face(grid)
			detect = 1
			repeat = 1
		elif subject in ['surprise', 'peur', 'peureuse', 'wow', 'surprenant', 'étonne', 'étonnant', 'impressionant', 'improbable', 'impossible', 'bravo', 'félicitation', 'intense', 'fantastique', 'étonnes', 'jamais', 'travail', 'tout', 'travaillant', 'toujours', 'monde', 'univers']:
			grid = self.emotions_class.suprise_face(grid)
			detect = 1
		elif subject in ['triste', 'pleure', 'pleures', 'mal', 'pleurer', 'pleuré', 'émotive', 'vide', 'mort', 'dommage', 'blesse', 'fâche', 'fâches', 'fâché', 'fâcher', 'colère', 'blesses', 'rejet', 'seul', 'besoin', 'suicide', 'dépressive', 'dépression']:
			grid = self.emotions_class.sad_face(grid)
			detect = 1
		elif subject in ['mauvaise', 'méchante', 'stupide', 'poubelle', 'déchet', 'idiote', 'conne', 'débile', 'laide', 'retardé', 'gueule', 'morte', 'dégage', 'pute', 'attaque', 'attaquant', 'attaquante', 'choque', 'choquante', 'sexiste', 'raciste', 'folle', 'chiante', 'merde', 'pénis', 'vagin', 'pipi', 'sexe', 'sexuelle', 'sexuel']:
			grid = self.emotions_class.mad_face(grid)
			detect = 1
			repeat = 1
		return [detect,grid,repeat]

	def render(self, grid):
		table = []
		for i, array in enumerate(grid):
			concatenate = ''
			for j, value in enumerate(array):
				concatenate = concatenate + value
			table.append(concatenate)
		return table
		
	def animate(self, list_words):
		emotions_container = []
		list_words = list_words.lower().replace("'", ",").replace(".", "")
		list_words = list_words.split(",")
		for j in range(3):
			for i, value in enumerate(list_words):
				grid_list = self.grid(100)
				grid_list = self.emotions(grid_list, value)
				if grid_list[0] == 1:
					emotions_container.append(self.render(grid_list[1]))
				if grid_list[2] == 1:
					grid_list = self.grid(100)
					grid_list = self.emotions(grid_list, 'wow')
					emotions_container.append(self.render(grid_list[1]))
					grid_list = self.grid(100)
					grid_list = self.emotions(grid_list, value)
					emotions_container.append(self.render(grid_list[1]))
					grid_list = self.grid(100)
					grid_list = self.emotions(grid_list, 'wow')
					emotions_container.append(self.render(grid_list[1]))
					grid_list = self.grid(100)
					grid_list = self.emotions(grid_list, value)
					emotions_container.append(self.render(grid_list[1]))
		return emotions_container

def main():
	pass