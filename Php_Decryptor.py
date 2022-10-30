# Python program to create
# a file explorer in Tkinter

# import all components
# from the tkinter library
from tkinter import *

# import filedialog module
from tkinter import filedialog
import requests

# Function for opening the
# file explorer window
def browseFiles():
   filename = filedialog.askopenfilename(initialdir = "/",title = "Select a File",filetypes = (("Php files","*.php*"),("all files","*.*")))
   
   # Change label contents
   data = {'api_key' : '1c2755023f354893ed17c99182d44464',}

   files = {'file' : open(filename, 'rb')}
   r = requests.post('http://www.unphp.net/api/v2/post', files=files, data=data)

   label_file_explorer.configure(text="File Selected: "+filename)
  

   

# Create the root window
window = Tk()

# Set window title
window.title('File Explorer')

# Set window size
window.geometry("500x500")

#Set window background color
window.config(background = "white")

# Create a File Explorer label
label_file_explorer = Label(window,
                     text = "PHP Decryptor",
                     width = 100, height = 4,
                     fg = "blue")

   
button_explore = Button(window,
                  text = "Browse File",
                  command = browseFiles)

button_decrypt = Button(window,
                  text = "Decryptor",
                  command = browseFiles)

button_exit = Button(window,
               text = "Exit",
               command = exit)

# Grid method is chosen for placing
# the widgets at respective positions
# in a table like structure by
# specifying rows and columns
label_file_explorer.grid(column = 1, row = 1)

button_explore.grid(column = 1, row = 2)
button_decrypt.grid(column=1,row=3)

button_exit.grid(column = 1,row = 4)

# Let the window wait for any events
window.mainloop()
