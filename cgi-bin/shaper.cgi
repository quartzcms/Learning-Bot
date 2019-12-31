#!/usr/bin/python3
print("Content-type: application/json; charset=UTF-8\n\n")
import sys
import json
import cgi
import cgitb
cgitb.enable(display=1)
sys.path.append("main")
sys.path.append("tools")
import controller.component as component
form = cgi.FieldStorage()
print(json.dumps(component.main(form)))