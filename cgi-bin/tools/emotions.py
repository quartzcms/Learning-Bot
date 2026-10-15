import sys
import cgi
import tools.shapes as shapes

R = 'rectangle'
O = 'ovale'
TU = 'triangle_up'
TD = 'triangle_down'

# Each part is a list of (shape, size, [[x0, x1], [y0, y1]]) tuples.
BASE_HEAD = [
	# forehead lines
	(R, [2, 40], [[95, 100], [0, 40]]),
	(R, [2, 40], [[0, 3], [0, 40]]),
	(R, [2, 1], [[90, 100], [0, 1]]),
	(R, [2, 1], [[0, 8], [0, 1]]),
	# nose
	(O, [2, 1], [[25, 70], [20, 50]]),
	(O, [2, 1], [[25, 80], [20, 50]]),
	(R, [2, 4], [[25, 75], [10, 50]]),
	# cheeks bottom
	(TU, [40, 10], [[0, 20], [20, 55]]),
	(TU, [40, 10], [[80, 100], [20, 55]]),
	(TU, [45, 25], [[0, 30], [20, 75]]),
	(TU, [45, 25], [[70, 100], [20, 75]]),
	(O, [35, 20], [[0, 20], [10, 85]]),
	(O, [35, 20], [[80, 100], [10, 85]]),
	# cheeks top
	(TU, [17, 20], [[0, 0], [10, 45]]),
	(TU, [17, 20], [[100, 100], [10, 45]]),
	# body
	(R, [100, 50], [[0, 100], [60, 100]]),
]

EYE_LEFT = (O, [25, 10], [[0, 50], [0, 50]])
EYE_RIGHT = (O, [25, 10], [[50, 100], [0, 50]])

EYES = {
	'open': [EYE_LEFT, EYE_RIGHT],
	'sad': [EYE_LEFT, EYE_RIGHT, (O, [2, 1], [[0, 50], [10, 50]]), (O, [2, 1], [[50, 100], [10, 50]])],
	'wide': [(O, [30, 16], [[0, 50], [0, 50]]), (O, [30, 16], [[50, 100], [0, 50]])],
	'closed': [(R, [22, 2], [[0, 50], [0, 50]]), (R, [22, 2], [[50, 100], [0, 50]])],
	'wink': [EYE_LEFT, (R, [22, 2], [[50, 100], [0, 50]])],
	'narrow': [(O, [25, 4], [[0, 50], [0, 50]]), (O, [25, 4], [[50, 100], [0, 50]])],
	'side': [EYE_LEFT, EYE_RIGHT, (R, [6, 10], [[0, 20], [0, 50]]), (R, [6, 10], [[50, 70], [0, 50]])],
	'love': [
		(O, [10, 10], [[13, 22], [0, 50]]), (O, [10, 10], [[28, 36], [0, 50]]),
		(O, [10, 10], [[63, 72], [0, 50]]), (O, [10, 10], [[78, 86], [0, 50]]),
		(TD, [26, 13], [[0, 50], [5, 52]]), (TD, [26, 13], [[50, 100], [5, 52]]),
	],
}

SMILE = [
	(R, [30, 1], [[25, 75], [45, 55]]),
	(O, [20, 1], [[15, 50], [50, 45]]), (O, [20, 1], [[55, 80], [50, 45]]),
	(R, [8, 1], [[15, 45], [35, 55]]), (R, [8, 1], [[55, 85], [35, 55]]),
]

MOUTHS = {
	'smile': SMILE,
	'frown': [
		(R, [30, 1], [[25, 75], [35, 55]]),
		(R, [4, 1], [[35, 35], [35, 58]]), (R, [4, 1], [[65, 65], [35, 58]]),
	],
	'talk': SMILE[:1] + [(R, [25, 1], [[15, 85], [45, 45]])] + SMILE[1:],
	'open': [(O, [25, 10], [[25, 75], [40, 60]])],
	'big_open': [(O, [40, 18], [[25, 75], [35, 65]])],
	'grin': [(O, [40, 14], [[25, 75], [40, 60]]), (R, [30, 1], [[25, 75], [45, 45]])],
	'small_o': [(O, [8, 8], [[25, 75], [40, 60]])],
	'flat': [(R, [30, 1], [[25, 75], [45, 55]])],
	'wavy': [(R, [14, 1], [[25, 50], [45, 55]]), (R, [14, 1], [[50, 75], [48, 58]])],
	'smirk': [(R, [14, 1], [[25, 50], [45, 55]]), (O, [14, 1], [[50, 80], [50, 45]]), (R, [5, 1], [[75, 85], [38, 55]])],
	'grit': [(R, [30, 6], [[25, 75], [40, 60]]), (R, [30, 1], [[25, 75], [50, 50]])],
	'tongue': SMILE + [(O, [10, 8], [[40, 60], [55, 75]])],
	'tremble': [(R, [6, 1], [[25, 40], [45, 55]]), (R, [6, 1], [[40, 55], [48, 58]]), (R, [6, 1], [[55, 70], [45, 55]])],
}

BROWS = {
	'normal': [
		(R, [20, 1], [[0, 50], [0, 20]]), (R, [20, 1], [[50, 100], [0, 20]]),
		(R, [15, 1], [[0, 30], [0, 25]]), (R, [15, 1], [[70, 100], [0, 25]]),
	],
	'angry': [
		(R, [20, 1], [[0, 50], [0, 20]]), (R, [20, 1], [[50, 100], [0, 20]]),
		(R, [15, 1], [[0, 30], [5, 15]]), (R, [15, 1], [[70, 100], [5, 15]]),
	],
	'raised': [
		(R, [20, 1], [[0, 50], [0, 10]]), (R, [20, 1], [[50, 100], [0, 10]]),
		(R, [15, 1], [[0, 30], [0, 15]]), (R, [15, 1], [[70, 100], [0, 15]]),
	],
	'one_raised': [
		(R, [20, 1], [[0, 50], [0, 20]]), (R, [20, 1], [[50, 100], [0, 8]]),
		(R, [15, 1], [[0, 30], [5, 15]]), (R, [15, 1], [[70, 100], [0, 12]]),
	],
	'worried': [
		(R, [20, 1], [[0, 50], [0, 12]]), (R, [20, 1], [[50, 100], [0, 12]]),
		(R, [15, 1], [[0, 30], [0, 20]]), (R, [15, 1], [[70, 100], [0, 20]]),
	],
}

# name: (eyes, mouth, brows)
FACES = {
	'happy': ('open', 'smile', 'normal'),
	'love': ('love', 'smile', 'normal'),
	'talk': ('open', 'talk', 'normal'),
	'surprise': ('open', 'open', 'normal'),
	'sad': ('sad', 'frown', 'normal'),
	'mad': ('open', 'frown', 'angry'),
	'angry': ('narrow', 'grit', 'angry'),
	'fear': ('wide', 'tremble', 'raised'),
	'shock': ('wide', 'big_open', 'raised'),
	'disgust': ('narrow', 'wavy', 'angry'),
	'sleepy': ('closed', 'small_o', 'normal'),
	'bored': ('narrow', 'flat', 'normal'),
	'confused': ('open', 'wavy', 'one_raised'),
	'wink': ('wink', 'smile', 'normal'),
	'laugh': ('closed', 'grin', 'raised'),
	'excited': ('wide', 'grin', 'raised'),
	'shy': ('side', 'small_o', 'worried'),
	'proud': ('open', 'smirk', 'normal'),
	'thinking': ('side', 'flat', 'one_raised'),
	'worried': ('open', 'tremble', 'worried'),
	'cry': ('sad', 'big_open', 'worried'),
	'playful': ('wink', 'tongue', 'normal'),
	'calm': ('closed', 'smile', 'normal'),
	'neutral': ('open', 'flat', 'normal'),
}

class Emotions():
	def __init__(self, form, output):
		self.form = form
		self.output = output
		self.shapes_class = shapes.Shapes(form, output)
		self.cache = {}

	def placeObject(self, object, grid, size, pointsWH):
		if object == 'triangle_up':
			output = self.shapes_class.triangle_up(grid, size, pointsWH)
		elif object == 'triangle_down':
			output = self.shapes_class.triangle_down(grid, size, pointsWH)
		elif object == 'ovale':
			output = self.shapes_class.ovale(grid, size, pointsWH)
		elif object == 'rectangle':
			output = self.shapes_class.rectangle(grid, size, pointsWH)
		return output

	def face(self, name, grid):
		if name not in FACES:
			name = 'neutral'
		key = (name, len(grid))
		if key not in self.cache:
			eyes, mouth, brows = FACES[name]
			blank = [['0'] * len(row) for row in grid]
			for part in (EYES[eyes], BASE_HEAD, MOUTHS[mouth], BROWS[brows]):
				for shape, size, points in part:
					blank = self.placeObject(shape, blank, size, points)
			self.cache[key] = blank
		for i, row in enumerate(self.cache[key]):
			for j, value in enumerate(row):
				if value == '1':
					grid[i][j] = '1'
		return grid

	def happy_face(self, grid):
		return self.face('happy', grid)

	def mad_face(self, grid):
		return self.face('mad', grid)

	def sad_face(self, grid):
		return self.face('sad', grid)

	def talk_face(self, grid):
		return self.face('talk', grid)

	def suprise_face(self, grid):
		return self.face('surprise', grid)

	def love_face(self, grid):
		return self.face('love', grid)

def main():
	pass
