import sys
import cgi
import tools.shapes as shapes

class Emotions():
	def __init__(self, form, output):
		self.form = form
		self.output = output
		self.shapes_class = shapes.Shapes(form, output)
	
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
	
	def happy_face(self, grid):
		#eyes
		grid = self.placeObject('ovale', grid, [25, 10], [[0, 50], [0, 50]])
		grid = self.placeObject('ovale', grid, [25, 10], [[50, 100], [0, 50]])

		#forhead lines
		grid = self.placeObject('rectangle', grid, [2, 40], [[95, 100], [0, 40]])
		grid = self.placeObject('rectangle', grid, [2, 40], [[0, 3], [0, 40]])
		grid = self.placeObject('rectangle', grid, [2, 1], [[90, 100], [0, 1]])
		grid = self.placeObject('rectangle', grid, [2, 1], [[0, 8], [0, 1]])

		#nose
		grid = self.placeObject('ovale', grid, [2, 1], [[25, 70], [20, 50]])
		grid = self.placeObject('ovale', grid, [2, 1], [[25, 80], [20, 50]])
		grid = self.placeObject('rectangle', grid, [2, 4], [[25, 75], [10, 50]])

		#cheeks bottom
		grid = self.placeObject('triangle_up', grid, [40, 10], [[0, 20], [20, 55]])
		grid = self.placeObject('triangle_up', grid, [40, 10], [[80, 100], [20, 55]])
		grid = self.placeObject('triangle_up', grid, [45, 25], [[0, 30], [20, 75]])
		grid = self.placeObject('triangle_up', grid, [45, 25], [[70, 100], [20, 75]])
		grid = self.placeObject('ovale', grid, [35, 20], [[0, 20], [10, 85]])
		grid = self.placeObject('ovale', grid, [35, 20], [[80, 100], [10, 85]])

		#cheeks top
		grid = self.placeObject('triangle_up', grid, [17, 20], [[0, 0], [10, 45]])
		grid = self.placeObject('triangle_up', grid, [17, 20], [[100, 100], [10, 45]])
		
		#body
		grid = self.placeObject('rectangle', grid, [100, 50], [[0, 100], [60, 100]])

		#mouth
		grid = self.placeObject('rectangle', grid, [30, 1], [[25, 75], [45, 55]])
		grid = self.placeObject('ovale', grid, [20, 1], [[15, 50], [50, 45]])
		grid = self.placeObject('ovale', grid, [20, 1], [[55, 80], [50, 45]])
		grid = self.placeObject('rectangle', grid, [8, 1], [[15, 45], [35, 55]])
		grid = self.placeObject('rectangle', grid, [8, 1], [[55, 85], [35, 55]])

		#eyesbrows
		grid = self.placeObject('rectangle', grid, [20, 1], [[0, 50], [0, 20]])
		grid = self.placeObject('rectangle', grid, [20, 1], [[50, 100], [0, 20]])
		grid = self.placeObject('rectangle', grid, [15, 1], [[0, 30], [0, 25]])
		grid = self.placeObject('rectangle', grid, [15, 1], [[70, 100], [0, 25]])
		
		return grid
	
	def mad_face(self, grid):
		#eyes
		grid = self.placeObject('ovale', grid, [25, 10], [[0, 50], [0, 50]])
		grid = self.placeObject('ovale', grid, [25, 10], [[50, 100], [0, 50]])

		#forhead lines
		grid = self.placeObject('rectangle', grid, [2, 40], [[95, 100], [0, 40]])
		grid = self.placeObject('rectangle', grid, [2, 40], [[0, 3], [0, 40]])
		grid = self.placeObject('rectangle', grid, [2, 1], [[90, 100], [0, 1]])
		grid = self.placeObject('rectangle', grid, [2, 1], [[0, 8], [0, 1]])

		#nose
		grid = self.placeObject('ovale', grid, [2, 1], [[25, 70], [20, 50]])
		grid = self.placeObject('ovale', grid, [2, 1], [[25, 80], [20, 50]])
		grid = self.placeObject('rectangle', grid, [2, 4], [[25, 75], [10, 50]])

		#cheeks bottom
		grid = self.placeObject('triangle_up', grid, [40, 10], [[0, 20], [20, 55]])
		grid = self.placeObject('triangle_up', grid, [40, 10], [[80, 100], [20, 55]])
		grid = self.placeObject('triangle_up', grid, [45, 25], [[0, 30], [20, 75]])
		grid = self.placeObject('triangle_up', grid, [45, 25], [[70, 100], [20, 75]])
		grid = self.placeObject('ovale', grid, [35, 20], [[0, 20], [10, 85]])
		grid = self.placeObject('ovale', grid, [35, 20], [[80, 100], [10, 85]])

		#cheeks top
		grid = self.placeObject('triangle_up', grid, [17, 20], [[0, 0], [10, 45]])
		grid = self.placeObject('triangle_up', grid, [17, 20], [[100, 100], [10, 45]])
		
		#body
		grid = self.placeObject('rectangle', grid, [100, 50], [[0, 100], [60, 100]])

		#mouth
		grid = self.placeObject('rectangle', grid, [30, 1], [[25, 75], [35, 55]])
		grid = self.placeObject('rectangle', grid, [4, 1], [[35, 35], [35, 58]])
		grid = self.placeObject('rectangle', grid, [4, 1], [[65, 65], [35, 58]])

		#eyesbrows
		grid = self.placeObject('rectangle', grid, [20, 1], [[0, 50], [0, 20]])
		grid = self.placeObject('rectangle', grid, [20, 1], [[50, 100], [0, 20]])
		grid = self.placeObject('rectangle', grid, [15, 1], [[0, 30], [5, 15]])
		grid = self.placeObject('rectangle', grid, [15, 1], [[70, 100], [5, 15]])
		
		return grid
		
	def sad_face(self, grid):
		#eyes
		grid = self.placeObject('ovale', grid, [25, 10], [[0, 50], [0, 50]])
		grid = self.placeObject('ovale', grid, [25, 10], [[50, 100], [0, 50]])
		grid = self.placeObject('ovale', grid, [2, 1], [[0, 50], [10, 50]])
		grid = self.placeObject('ovale', grid, [2, 1], [[50, 100], [10, 50]])

		#forhead lines
		grid = self.placeObject('rectangle', grid, [2, 40], [[95, 100], [0, 40]])
		grid = self.placeObject('rectangle', grid, [2, 40], [[0, 3], [0, 40]])
		grid = self.placeObject('rectangle', grid, [2, 1], [[90, 100], [0, 1]])
		grid = self.placeObject('rectangle', grid, [2, 1], [[0, 8], [0, 1]])

		#nose
		grid = self.placeObject('ovale', grid, [2, 1], [[25, 70], [20, 50]])
		grid = self.placeObject('ovale', grid, [2, 1], [[25, 80], [20, 50]])
		grid = self.placeObject('rectangle', grid, [2, 4], [[25, 75], [10, 50]])

		#cheeks bottom
		grid = self.placeObject('triangle_up', grid, [40, 10], [[0, 20], [20, 55]])
		grid = self.placeObject('triangle_up', grid, [40, 10], [[80, 100], [20, 55]])
		grid = self.placeObject('triangle_up', grid, [45, 25], [[0, 30], [20, 75]])
		grid = self.placeObject('triangle_up', grid, [45, 25], [[70, 100], [20, 75]])
		grid = self.placeObject('ovale', grid, [35, 20], [[0, 20], [10, 85]])
		grid = self.placeObject('ovale', grid, [35, 20], [[80, 100], [10, 85]])

		#cheeks top
		grid = self.placeObject('triangle_up', grid, [17, 20], [[0, 0], [10, 45]])
		grid = self.placeObject('triangle_up', grid, [17, 20], [[100, 100], [10, 45]])
		
		#body
		grid = self.placeObject('rectangle', grid, [100, 50], [[0, 100], [60, 100]])

		#mouth
		grid = self.placeObject('rectangle', grid, [30, 1], [[25, 75], [35, 55]])
		grid = self.placeObject('rectangle', grid, [4, 1], [[35, 35], [35, 58]])
		grid = self.placeObject('rectangle', grid, [4, 1], [[65, 65], [35, 58]])

		#eyesbrows
		grid = self.placeObject('rectangle', grid, [20, 1], [[0, 50], [0, 20]])
		grid = self.placeObject('rectangle', grid, [20, 1], [[50, 100], [0, 20]])
		grid = self.placeObject('rectangle', grid, [15, 1], [[0, 30], [0, 25]])
		grid = self.placeObject('rectangle', grid, [15, 1], [[70, 100], [0, 25]])
		
		return grid
		
	def talk_face(self, grid):
		#eyes
		grid = self.placeObject('ovale', grid, [25, 10], [[0, 50], [0, 50]])
		grid = self.placeObject('ovale', grid, [25, 10], [[50, 100], [0, 50]])

		#forhead lines
		grid = self.placeObject('rectangle', grid, [2, 40], [[95, 100], [0, 40]])
		grid = self.placeObject('rectangle', grid, [2, 40], [[0, 3], [0, 40]])
		grid = self.placeObject('rectangle', grid, [2, 1], [[90, 100], [0, 1]])
		grid = self.placeObject('rectangle', grid, [2, 1], [[0, 8], [0, 1]])

		#nose
		grid = self.placeObject('ovale', grid, [2, 1], [[25, 70], [20, 50]])
		grid = self.placeObject('ovale', grid, [2, 1], [[25, 80], [20, 50]])
		grid = self.placeObject('rectangle', grid, [2, 4], [[25, 75], [10, 50]])

		#cheeks bottom
		grid = self.placeObject('triangle_up', grid, [40, 10], [[0, 20], [20, 55]])
		grid = self.placeObject('triangle_up', grid, [40, 10], [[80, 100], [20, 55]])
		grid = self.placeObject('triangle_up', grid, [45, 25], [[0, 30], [20, 75]])
		grid = self.placeObject('triangle_up', grid, [45, 25], [[70, 100], [20, 75]])
		grid = self.placeObject('ovale', grid, [35, 20], [[0, 20], [10, 85]])
		grid = self.placeObject('ovale', grid, [35, 20], [[80, 100], [10, 85]])

		#cheeks top
		grid = self.placeObject('triangle_up', grid, [17, 20], [[0, 0], [10, 45]])
		grid = self.placeObject('triangle_up', grid, [17, 20], [[100, 100], [10, 45]])
		
		#body
		grid = self.placeObject('rectangle', grid, [100, 50], [[0, 100], [60, 100]])

		#mouth
		grid = self.placeObject('rectangle', grid, [30, 1], [[25, 75], [45, 55]])
		grid = self.placeObject('rectangle', grid, [25, 1], [[15, 85], [45, 45]])
		grid = self.placeObject('ovale', grid, [20, 1], [[15, 50], [50, 45]])
		grid = self.placeObject('ovale', grid, [20, 1], [[55, 80], [50, 45]])
		grid = self.placeObject('rectangle', grid, [8, 1], [[15, 45], [35, 55]])
		grid = self.placeObject('rectangle', grid, [8, 1], [[55, 85], [35, 55]])

		#eyesbrows
		grid = self.placeObject('rectangle', grid, [20, 1], [[0, 50], [0, 20]])
		grid = self.placeObject('rectangle', grid, [20, 1], [[50, 100], [0, 20]])
		grid = self.placeObject('rectangle', grid, [15, 1], [[0, 30], [0, 25]])
		grid = self.placeObject('rectangle', grid, [15, 1], [[70, 100], [0, 25]])
		
		return grid
		
	def suprise_face(self, grid):
		#eyes
		grid = self.placeObject('ovale', grid, [25, 10], [[0, 50], [0, 50]])
		grid = self.placeObject('ovale', grid, [25, 10], [[50, 100], [0, 50]])

		#forhead lines
		grid = self.placeObject('rectangle', grid, [2, 40], [[95, 100], [0, 40]])
		grid = self.placeObject('rectangle', grid, [2, 40], [[0, 3], [0, 40]])
		grid = self.placeObject('rectangle', grid, [2, 1], [[90, 100], [0, 1]])
		grid = self.placeObject('rectangle', grid, [2, 1], [[0, 8], [0, 1]])

		#nose
		grid = self.placeObject('ovale', grid, [2, 1], [[25, 70], [20, 50]])
		grid = self.placeObject('ovale', grid, [2, 1], [[25, 80], [20, 50]])
		grid = self.placeObject('rectangle', grid, [2, 4], [[25, 75], [10, 50]])

		#cheeks bottom
		grid = self.placeObject('triangle_up', grid, [40, 10], [[0, 20], [20, 55]])
		grid = self.placeObject('triangle_up', grid, [40, 10], [[80, 100], [20, 55]])
		grid = self.placeObject('triangle_up', grid, [45, 25], [[0, 30], [20, 75]])
		grid = self.placeObject('triangle_up', grid, [45, 25], [[70, 100], [20, 75]])
		grid = self.placeObject('ovale', grid, [35, 20], [[0, 20], [10, 85]])
		grid = self.placeObject('ovale', grid, [35, 20], [[80, 100], [10, 85]])

		#cheeks top
		grid = self.placeObject('triangle_up', grid, [17, 20], [[0, 0], [10, 45]])
		grid = self.placeObject('triangle_up', grid, [17, 20], [[100, 100], [10, 45]])
		
		#body
		grid = self.placeObject('rectangle', grid, [100, 50], [[0, 100], [60, 100]])

		#mouth
		grid = self.placeObject('ovale', grid, [25, 10], [[25, 75], [40, 60]])

		#eyesbrows
		grid = self.placeObject('rectangle', grid, [20, 1], [[0, 50], [0, 20]])
		grid = self.placeObject('rectangle', grid, [20, 1], [[50, 100], [0, 20]])
		grid = self.placeObject('rectangle', grid, [15, 1], [[0, 30], [0, 25]])
		grid = self.placeObject('rectangle', grid, [15, 1], [[70, 100], [0, 25]])
		
		return grid

	def love_face(self, grid):
		#eyes
		grid = self.placeObject('ovale', grid, [10, 10], [[13, 22], [0, 50]])
		grid = self.placeObject('ovale', grid, [10, 10], [[28, 36], [0, 50]])
		grid = self.placeObject('ovale', grid, [10, 10], [[63, 72], [0, 50]])
		grid = self.placeObject('ovale', grid, [10, 10], [[78, 86], [0, 50]])
		grid = self.placeObject('triangle_down', grid, [26, 13], [[0, 50], [5, 52]])
		grid = self.placeObject('triangle_down', grid, [26, 13], [[50, 100], [5, 52]])

		#forhead lines
		grid = self.placeObject('rectangle', grid, [2, 40], [[95, 100], [0, 40]])
		grid = self.placeObject('rectangle', grid, [2, 40], [[0, 3], [0, 40]])
		grid = self.placeObject('rectangle', grid, [2, 1], [[90, 100], [0, 1]])
		grid = self.placeObject('rectangle', grid, [2, 1], [[0, 8], [0, 1]])

		#nose
		grid = self.placeObject('ovale', grid, [2, 1], [[25, 70], [20, 50]])
		grid = self.placeObject('ovale', grid, [2, 1], [[25, 80], [20, 50]])
		grid = self.placeObject('rectangle', grid, [2, 4], [[25, 75], [10, 50]])

		#cheeks bottom
		grid = self.placeObject('triangle_up', grid, [40, 10], [[0, 20], [20, 55]])
		grid = self.placeObject('triangle_up', grid, [40, 10], [[80, 100], [20, 55]])
		grid = self.placeObject('triangle_up', grid, [45, 25], [[0, 30], [20, 75]])
		grid = self.placeObject('triangle_up', grid, [45, 25], [[70, 100], [20, 75]])
		grid = self.placeObject('ovale', grid, [35, 20], [[0, 20], [10, 85]])
		grid = self.placeObject('ovale', grid, [35, 20], [[80, 100], [10, 85]])

		#cheeks top
		grid = self.placeObject('triangle_up', grid, [17, 20], [[0, 0], [10, 45]])
		grid = self.placeObject('triangle_up', grid, [17, 20], [[100, 100], [10, 45]])
		
		#body
		grid = self.placeObject('rectangle', grid, [100, 50], [[0, 100], [60, 100]])

		#mouth
		grid = self.placeObject('rectangle', grid, [30, 1], [[25, 75], [45, 55]])
		grid = self.placeObject('ovale', grid, [20, 1], [[15, 50], [50, 45]])
		grid = self.placeObject('ovale', grid, [20, 1], [[55, 80], [50, 45]])
		grid = self.placeObject('rectangle', grid, [8, 1], [[15, 45], [35, 55]])
		grid = self.placeObject('rectangle', grid, [8, 1], [[55, 85], [35, 55]])

		#eyesbrows
		grid = self.placeObject('rectangle', grid, [20, 1], [[0, 50], [0, 20]])
		grid = self.placeObject('rectangle', grid, [20, 1], [[50, 100], [0, 20]])
		grid = self.placeObject('rectangle', grid, [15, 1], [[0, 30], [0, 25]])
		grid = self.placeObject('rectangle', grid, [15, 1], [[70, 100], [0, 25]])
		
		return grid

def main():
	pass