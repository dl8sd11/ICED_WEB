import tkinter as tk


def check_input(input):
    if not(input.isdigit()): #檢查是否只包含數字
        print("輸入只能包含數字")


def check_ab():
    user_input = guess_entry.get() #取得使用者輸入
    guess_entry.delete(0,tk.END) #清空guess_entry
    if user_input=="": #防止空白猜測
        return
    if not(check_input(user_input)): #檢查不合法猜測
        return

root =tk.Tk() #宣告主視窗

num_label = tk.Label(root,text="輸入數字") #提示標籤
guess_entry = tk.Entry(root) #使用者的輸入
submit_button = tk.Button(root,text="猜",command=check_ab) #提交答案按鈕
result_label = tk.Label(root,text="") #回傳的結果

num_label.grid(row=0,column=0)
guess_entry.grid(row=0,column=1)
submit_button.grid(row=0,column=2)
result_label.grid(row=1,column=0) #把物件排版到視窗上

root.mainloop()
