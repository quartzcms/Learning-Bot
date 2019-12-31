import cgi
import sys
import tools.grid as grid

class Shaper():
	def __init__(self, form, output):
		self.form = form
		self.output = output
		self.grid_class = grid.Grid(form, output)
	
	def getList(self):
		try:
			form_field =  self.form['list'].value
		except KeyError:
			form_field =  ""
		return form_field
	
	def selection(self):
		return self.grid_class.animate(self.getList())

def main(form_cgi):	
	shaper_class = Shaper(form_cgi, {})
	return shaper_class.selection()