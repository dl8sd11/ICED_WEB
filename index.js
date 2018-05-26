var express = require('express');
const fileUpload = require('express-fileupload');
var app = express();
var server = require('http').createServer(app);
var io = require('socket.io')(server);
var fs = require('fs');
var util = require('util');

var obj;
app.use(express.static('public'));
app.use(fileUpload());

fs.readFile('question.json', 'utf8', function (err, data) {
  if (err) throw err;
  obj = JSON.parse(data);
});
fs.readFile('group_code.json', 'utf8', function (err, data) {
  if (err) throw err;
  group_code = JSON.parse(data);
});


app.get('/', function (req, res) {
   res.send('Hello World');
})
app.post('/upload',function(req,res){
  if(!req.files)
    return res.status(400).send('No files were uploaded.');
  let code = req.files.code;
  code.mv('./codes/'+code.name,function(err){
    if(err)
      return res.status(500).send(err);
    res.send('File uploaded');
  });
});
server.listen(80);

io.on('connection',function(client){
  client.on('getQs',function(data){
    client.emit('sendQs',obj);
  });
  client.on('newQ',function(data){
    data.id=obj.cnt;
    obj.questions.push(data);
    obj.cnt++;
    fs.writeFileSync('./question.json',JSON.stringify(obj),'utf-8');
    io.sockets.emit('sendQs',obj);

  });
  client.on("check_members",function(data){
    let query = null;
    for(let i=0;i<group_code.group_cnt;i++){
      let now = group_code.groups[i];
      for(let j=0;j<now.members.length;j++){
        if(now.members[j]==data)query=now.members;
      }
    }
    if(query)client.emit('update_members',query);
    else client.emit('update_members',null);
  });
})

var stdin = process.openStdin();

stdin.addListener("data", function(d) {
  let input = d.toString().trim();
  if(input=="/clearall"){
    obj={"cnt":0,"questions":[]};
    fs.writeFileSync('./question.json',JSON.stringify(obj),'utf-8');
    io.sockets.emit('sendQs',obj);
  }
});
