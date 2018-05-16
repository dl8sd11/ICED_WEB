import tkinter as tk

class game:
    def check_win(self):
        for x in range(3):
            if self.board[x*3]==self.board[x*3+1] and self.board[x*3+1] == self.board[x*3+2] and self.board[x*3] != '.':
                for y in range(3):
                    self.buttons[x*3+y]['disabledforeground'] = 'red'
                    self.buttons[x*3+y]['state'] = 'disabled'

        for x in range(3):
            if self.board[x]==self.board[x+3] and self.board[x+3] == self.board[x+6] and self.board[x] != '.':
                for y in range(3):
                    self.buttons[x+y*3]['disabledforeground'] = 'red'
                    self.buttons[x+y*3]['state'] = 'disabled'

        if self.board[0]==self.board[4] and self.board[4] == self.board[8] and self.board[0] != '.':
            self.buttons[0]['disabledforeground'] = 'red'
            self.buttons[0]['state'] = 'disabled'
            self.buttons[4]['disabledforeground'] = 'red'
            self.buttons[4]['state'] = 'disabled'
            self.buttons[8]['disabledforeground'] = 'red'
            self.buttons[8]['state'] = 'disabled'

        if self.board[2]==self.board[4] and self.board[4] == self.board[6] and self.board[2] != '.':
            self.buttons[2]['disabledforeground'] = 'red'
            self.buttons[2]['state'] = 'disabled'
            self.buttons[4]['disabledforeground'] = 'red'
            self.buttons[4]['state'] = 'disabled'
            self.buttons[6]['disabledforeground'] = 'red'
            self.buttons[6]['state'] = 'disabled'

    def button_click(self,x):
        self.buttons[x]['text']=self.now_player
        self.buttons[x]['disabledforeground'] = 'black'
        self.buttons[x]['state'] = 'disabled'
        self.board[x] = self.now_player
        if self.now_player =="X":
            self.now_player = "O"
        else:
            self.now_player = "X"
        self.check_win()

    def __init__(self):
        self.root = tk.Tk()
        self.buttons = []
        self.board = []
        for i in range(9):
            self.board.append('.')
        self.now_player = "X"
        for x in range(9):
            button = tk.Button(self.root,font=("Helvetica",32),command=lambda idx=x:  self.button_click(idx),height=1,width=2,text=".")
            button.grid(row=int(x/3),column=x%3)
            self.buttons.append(button)

    def mainloop(self):
        self.root.mainloop()
game().mainloop()
