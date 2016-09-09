create table usergroups (
  id int not null primary key auto_increment,
  plunetid varchar(16),
  type enum('customer','vendor','internal','partner'),
  company varchar(64),
  contactfirst varchar(50),
  contactlast varchar(50),
  address varchar(50),
  city varchar(50),
  state varchar(16),
  zip varchar(10),
  country varchar(50),
  phone varchar(20),
  email varchar(64),
  created datetime,
  modified datetime  
  lastactivity datetime
);

create table users (
  id int not null primary key auto_increment,
  plunetid varchar(16),
  firstname varchar(50),
  lastname varchar(50),
  email varchar(50),
  password varchar(150),
  usergroup int references usergroups(id),
  issiteadmin tinyint(1) default 0,
  iscompanyadmin tinyint(1) default 0,
  apienable tinyint(1) default 0,
  portalenable tinyint(1) default 0,
  customer int references customers(id),
  created datetime,
  modified datetime
);

create table revocations (
  id int not null primary key auto_increment,
  user int references users(id),
  iat int,
  comment varchar(512)
);
create table tokens (
  id int not null primary key auto_increment,
  user int references users(id),
  value varchar(500),
  created datetime
);

create table languages (
  id int not null primary key auto_increment,
  code varchar(10),
  lang varchar(4),
  region varchar(4),
  script varchar(12),
  description varchar(64)
);

create table languagepairs (
  src int references languages(id),
  tgt int references languages(id)
);

create table orders (
  id int not null primary key auto_increment,
  user int references users(id),
  customer int references customers(id),
  requestid varchar(10),
  orderid varchar(10),
  name varchar(128),
  comments text,
  status enum('new','preparation','quoted','approved','inprogress','complete'),
  quoteneeded tinyint(1) default 0,
  rushorder tinyint(1) default 0,
  duedate datetime,
  created datetime,
  modified datetime
);

create table files (
  id int not null primary key auto_increment,
  user int references users(id),
  orderid int references orders(id),
  sourcelang int references languages(id),
  targetlang int references languages(id),
  filename varchar(64),
  filetype varchar(16),
  encoding varchar(16),
  uploaded datetime,
  status enum('new','preparation','inprogress','complete'),
  clientid varchar(80),
  callback varchar(80),
  comments text
);

create table history (
  id int not null primary key auto_increment,
  user int references users(id),
  action enum('insert','update','delete'),
  when datetime,
  details varchar(128)
);

create table callbacks (
  id int not null primary key auto_increment,
  cbtype enum('order','file'),
  objid int,
  url varchar(128),
  status enum('active','inactive','failed')
);

create table tasks (
  id int not null primary key auto_increment,
  queued datetime,
  tasktype enum('callback'),
  target int,
  info varchar(128),
  status enum('queued','processing','complete','failed')
);
