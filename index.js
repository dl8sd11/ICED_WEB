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
  client.emit('init',true);
  client.on('newQ',function(data){
    data.id=obj.cnt;
    obj.questions.push(data);
    obj.cnt++;
    fs.writeFileSync('./question.json',JSON.stringify(obj),'utf-8');
    io.sockets.emit('sendQs',obj);

  });
  client.on('getQs',function(){
    client.emit('sendQs',obj);
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
  client.on("send_code",function(data){
    let query = null;
    let now;
    for(let i=0;i<group_code.group_cnt;i++){
      now = group_code.groups[i];
      for(let j=0;j<now.members.length;j++){
        if(now.members[j]==data.name)query=now;
      }
    }
    if(query){
      if(data.vcode==query.code){
        client.emit('send_success',true);
        let dir = "./codes/"+data.homework+"/"+data.name;
        if (!fs.existsSync(dir)){
          fs.mkdirSync(dir);
        }
        fs.writeFile(dir+"/"+Math.floor(Date.now() / 1000)+".py", data.source_code, function(err) {
            if(err) {
                return console.log(err);
            }
            console.log("The file from "+data.name+" was saved!");
        }); 
      } else {
        client.emit('send_success',false);
      }
    } else {
      client.emit("update_members",null);
    }
  });
  client.on('request_homework_update',function(){
    let homework_options = [];
    fs.readdir('./codes',function(err,files){
      if(err)throw err;
      for(let i=0;i<=files.length;i++){
        if(i==files.length)client.emit('update_homework_options',homework_options);
        else if(files[i][0]!='.')homework_options.push(files[i]);
      } 
    });
    
  })
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
