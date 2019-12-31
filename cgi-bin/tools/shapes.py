import sys
import cgi
import os

class Shapes():
	def __init__(self, form, output):
		self.form = form
		self.output = output
		
	def ovale(self, grid, size, pointsWH):
		x = (size[0] // 5)
		for i, array in enumerate(grid):
			spacing_height = round(((pointsWH[1][1] - pointsWH[1][0]) / 2) - (size[1] / 2))
			bottom_side = round(spacing_height + size[1] + pointsWH[1][0])
			top_side = round(spacing_height + pointsWH[1][0])
			if i >= top_side and i <= bottom_side:
				if i == round(top_side + (size[1] / 2)):
					x = (size[0] // 5)
				for j, value in enumerate(array):
					spacing_width = round(((pointsWH[0][1] - pointsWH[0][0]) / 2) - (size[0] / 2))
					right_side = round(spacing_width + size[0] + pointsWH[0][0])
					left_side = round(spacing_width + pointsWH[0][0])
					if i >= round(top_side + (size[1] / 2)):
						x_side_left = round(left_side + x)
						x_side_right = round(right_side - x)
					else:
						x_side_left = round((left_side + (size[0] / 2)) - x)
						x_side_right = round((left_side + (size[0] / 2)) + x)
					if j >= left_side and j <= right_side and j >= x_side_left and j <= x_side_right:
						grid[i][j] = '1'
				x += (size[0] // 5)
		return grid
		
	def triangle_up(self, grid, size, pointsWH):
		x = 1
		for i, array in enumerate(grid):
			spacing_height = round(((pointsWH[1][1] - pointsWH[1][0]) / 2) - (size[1] / 2))
			bottom_side = round(spacing_height + size[1] + pointsWH[1][0])
			top_side = round(spacing_height + pointsWH[1][0])
			if i >= top_side and i <= bottom_side:
				for j, value in enumerate(array):
					spacing_width = round(((pointsWH[0][1] - pointsWH[0][0]) / 2) - (size[0] / 2))
					right_side = round(spacing_width + size[0] + pointsWH[0][0])
					left_side = round(spacing_width + pointsWH[0][0])
					x_side_left = round((left_side + (size[0] / 2)) - x)
					x_side_right = round((left_side + (size[0] / 2)) + x)
					if j >= left_side and j <= right_side and j >= x_side_left and j <= x_side_right:
						grid[i][j] = '1'
				x += 1
		return grid
		
	def triangle_down(self, grid, size, pointsWH):
		x = 1
		for i, array in enumerate(grid):
			spacing_height = round(((pointsWH[1][1] - pointsWH[1][0]) / 2) - (size[1] / 2))
			bottom_side = round(spacing_height + size[1] + pointsWH[1][0])
			top_side = round(spacing_height + pointsWH[1][0])
			if i >= top_side and i <= bottom_side:
				for j, value in enumerate(array):
					spacing_width = round(((pointsWH[0][1] - pointsWH[0][0]) / 2) - (size[0] / 2))
					right_side = round(spacing_width + size[0] + pointsWH[0][0])
					left_side = round(spacing_width + pointsWH[0][0])
					x_side_left = round(left_side + x)
					x_side_right = round(right_side - x)
					if j >= left_side and j <= right_side and j >= x_side_left and j <= x_side_right:
						grid[i][j] = '1'
				x += 1
		return grid
		
	def rectangle(self, grid, size, pointsWH):
		for i, array in enumerate(grid):
			spacing_height = round(((pointsWH[1][1] - pointsWH[1][0]) / 2) - (size[1] / 2))
			bottom_side = round(spacing_height + size[1] + pointsWH[1][0])
			top_side = round(spacing_height + pointsWH[1][0])
			if i >= top_side and i <= bottom_side:
				for j, value in enumerate(array):
					spacing_width = round(((pointsWH[0][1] - pointsWH[0][0]) / 2) - (size[0] / 2))
					right_side = round(spacing_width + size[0] + pointsWH[0][0])
					left_side = round(spacing_width + pointsWH[0][0])
					if j >= left_side and j <= right_side:
						grid[i][j] = '1'
		return grid
		
def main():
	pass