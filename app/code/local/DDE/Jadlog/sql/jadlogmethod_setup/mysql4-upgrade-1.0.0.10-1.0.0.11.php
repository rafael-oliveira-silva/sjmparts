﻿<?php

$installer = $this;

$installer->startSetup();

$installer->run('
	INSERT INTO '.$installer->getTable('jadlogmethod/cep').'( estado, localidade, cep_inicial, cep_final, standard, rodoviario, package, economico, doc, corporate, com, internacional, cargo, emergencia ) VALUES("PB","Duas Estradas","58265000","58267999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Sertãozinho","58268000","58269999","8","12", "", "", "", "", "", "", "", ""),
		("PB","Araçagi","58270000","58272999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Pedro Régis","58273000","58274999","8","12", "", "", "", "", "", "", "", ""),
		("PB","Itapororoca","58275000","58277999","7","11", "", "", "", "", "", "", "", ""),
		("PB","Jacaraú","58278000","58279999","8","12", "", "", "", "", "", "", "", ""),
		("PB","Mamanguape","58280000","58286999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Capim","58287000","58288999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Cuité de Mamanguape","58289000","58290999","8","12", "", "", "", "", "", "", "", ""),
		("PB","Curral de Cima","58291000","58291999","8","12", "", "", "", "", "", "", "", ""),
		("PB","Mataraca","58292000","58293999","8","12", "", "", "", "", "", "", "", ""),
		("PB","Marcação","58294000","58294999","7","11", "", "", "", "", "", "", "", ""),
		("PB","Baía da Traição","58295000","58296999","6","10", "", "", "", "", "", "", "", ""),
		("PB","Rio Tinto","58297000","58299999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Santa Rita","58300000","58303999","3","6", "", "", "", "", "", "", "", ""),
		("PB","Bayeux","58305000","58309999","2","6", "", "", "", "", "", "", "", ""),
		("PB","Cabedelo","58310000","58314999","8","11", "", "", "", "", "", "", "", ""),
		("PB","Lucena","58315000","58319999","7","11", "", "", "", "", "", "", "", ""),
		("PB","Alhandra","58320000","58321999","4","8", "", "", "", "", "", "", "", ""),
		("PB","Conde","58322000","58323999","5","7", "", "", "", "", "", "", "", ""),
		("PB","Pitimbu","58324000","58325999","7","11", "", "", "", "", "", "", "", ""),
		("PB","Caaporã","58326000","58327999","6","11", "", "", "", "", "", "", "", ""),
		("PB","Pedras de Fogo","58328000","58329999","7","11", "", "", "", "", "", "", "", ""),
		("PB","Juripiranga","58330000","58333999","8","12", "", "", "", "", "", "", "", ""),
		("PB","São Miguel de Taipu","58334000","58336999","7","11", "", "", "", "", "", "", "", ""),
		("PB","Cruz do Espírito Santo","58337000","58337999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Pilar","58338000","58338999","8","12", "", "", "", "", "", "", "", ""),
		("PB","São José dos Ramos","58339000","58339999","8","12", "", "", "", "", "", "", "", ""),
		("PB","Sapé","58340000","58341999","4","8", "", "", "", "", "", "", "", ""),
		("PB","Sobrado","58342000","58344999","6","10", "", "", "", "", "", "", "", ""),
		("PB","Mari","58345000","58347999","4","8", "", "", "", "", "", "", "", ""),
		("PB","Riachão do Poço","58348000","58349999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Caldas Brandão","58350000","58353999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Mulungu","58354000","58355999","8","12", "", "", "", "", "", "", "", ""),
		("PB","Gurinhém","58356000","58359999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Itabaiana","58360000","58369999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Salgado de São Félix","58370000","58374999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Mogeiro","58375000","58377999","8","12", "", "", "", "", "", "", "", ""),
		("PB","Itatuba","58378000","58379999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Ingá","58380000","58381999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Riachão do Bacamarte","58382000","58384999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Serra Redonda","58385000","58386999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Juarez Távora","58387000","58387999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Alagoa Grande","58388000","58389999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Alagoinha","58390000","58392999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Pilões","58393000","58393999","8","12", "", "", "", "", "", "", "", ""),
		("PB","Borborema","58394000","58394999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Serraria","58395000","58395999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Arara","58396000","58396999","8","12", "", "", "", "", "", "", "", ""),
		("PB","Areia","58397000","58397999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Remígio","58398000","58398999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Algodão de Jandaíra","58399000","58399999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Campina Grande","58400000","58439999","2","6", "", "", "", "", "", "", "", ""),
		("PB","Boqueirão","58450000","58454999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Caturité","58455000","58457999","8","12", "", "", "", "", "", "", "", ""),
		("PB","Barra de Santana","58458000","58459999","8","12", "", "", "", "", "", "", "", ""),
		("PB","Alcantil","58460000","58462999","7","11", "", "", "", "", "", "", "", ""),
		("PB","Santa Cecília","58463000","58464999","8","12", "", "", "", "", "", "", "", ""),
		("PB","Riacho de Santo Antônio","58465000","58474999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Queimadas","58475000","58479999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Cabaceiras","58480000","58482999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Barra de São Miguel","58483000","58484999","5","9", "", "", "", "", "", "", "", ""),
		("PB","São Domingos do Cariri","58485000","58488999","8","12", "", "", "", "", "", "", "", ""),
		("PB","Aroeiras","58489000","58493999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Natuba","58494000","58499999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Monteiro","58500000","58509999","9","13", "", "", "", "", "", "", "", ""),
		("PB","São Sebastião do Umbuzeiro","58510000","58514999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Zabelê","58515000","58519999","6","10", "", "", "", "", "", "", "", ""),
		("PB","São João do Tigre","58520000","58529999","8","12", "", "", "", "", "", "", "", ""),
		("PB","Camalaú","58530000","58534999","8","12", "", "", "", "", "", "", "", ""),
		("PB","Congo","58535000","58539999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Sumé","58540000","58547999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Amparo","58548000","58549999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Prata","58550000","58559999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Ouro Velho","58560000","58569999","7","11", "", "", "", "", "", "", "", ""),
		("PB","São José dos Cordeiros","58570000","58574999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Parari","58575000","58579999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Serra Branca","58580000","58587999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Coxixola","58588000","58589999","8","12", "", "", "", "", "", "", "", ""),
		("PB","São João do Cariri","58590000","58594999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Caraúbas","58595000","58599999","12","16", "", "", "", "", "", "", "", ""),
		("PB","Santa Luzia","58600000","58609999","5","9", "", "", "", "", "", "", "", ""),
		("PB","São José do Sabugi","58610000","58619999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Várzea","58620000","58624999","9","13", "", "", "", "", "", "", "", ""),
		("PB","São Mamede","58625000","58639999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Junco do Seridó","58640000","58649999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Salgadinho","58650000","58659999","8","12", "", "", "", "", "", "", "", ""),
		("PB","Juazeirinho","58660000","58664999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Tenório","58665000","58669999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Gurjão","58670000","58674999","7","11", "", "", "", "", "", "", "", ""),
		("PB","Santo André","58675000","58679999","8","12", "", "", "", "", "", "", "", ""),
		("PB","Taperoá","58680000","58684999","8","12", "", "", "", "", "", "", "", ""),
		("PB","Assunção","58685000","58689999","8","12", "", "", "", "", "", "", "", ""),
		("PB","Livramento","58690000","58694999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Desterro","58695000","58697999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Cacimbas","58698000","58699999","8","12", "", "", "", "", "", "", "", ""),
		("PB","Patos","58700000","58708999","8","12", "", "", "", "", "", "", "", ""),
		("PB","Vista Serrana","58710000","58712999","6","10", "", "", "", "", "", "", "", ""),
		("PB","Malta","58713000","58713999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Condado","58714000","58714999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Catingueira","58715000","58719999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Santa Teresinha","58720000","58722999","8","12", "", "", "", "", "", "", "", ""),
		("PB","São José de Espinharas","58723000","58724999","9","13", "", "", "", "", "", "", "", ""),
		("PB","São José do Bonfim","58725000","58729999","8","12", "", "", "", "", "", "", "", ""),
		("PB","Cacimba de Areia","58730000","58731999","8","12", "", "", "", "", "", "", "", ""),
		("PB","Areia de Baraúnas","58732000","58732999","8","12", "", "", "", "", "", "", "", ""),
		("PB","Quixabá","58733000","58733999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Passagem","58734000","58734999","8","12", "", "", "", "", "", "", "", ""),
		("PB","Teixeira","58735000","58736999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Maturéia","58737000","58739999","8","12", "", "", "", "", "", "", "", ""),
		("PB","Mãe D\'Água","58740000","58744999","8","12", "", "", "", "", "", "", "", ""),
		("PB","Imaculada","58745000","58747999","8","12", "", "", "", "", "", "", "", ""),
		("PB","Água Branca","58748000","58749999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Juru","58750000","58752999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Tavares","58753000","58754999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Princesa Isabel","58755000","58757999","9","13", "", "", "", "", "", "", "", ""),
		("PB","São José de Princesa","58758000","58759999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Olho D\'Água","58760000","58762999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Emas","58763000","58764999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Piancó","58765000","58769999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Coremas","58770000","58774999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Igaracy","58775000","58777999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Aguiar","58778000","58779999","8","12", "", "", "", "", "", "", "", ""),
		("PB","Itaporanga","58780000","58783999","8","12", "", "", "", "", "", "", "", ""),
		("PB","São José de Caiana","58784000","58789999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Pedra Branca","58790000","58794999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Santana dos Garrotes","58795000","58797999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Nova Olinda","58798000","58799999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Sousa","58800000","58809999","9","13", "", "", "", "", "", "", "", ""),
		("PB","São José da Lagoa Tapada","58815000","58816999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Nazarezinho","58817000","58817999","5","9", "", "", "", "", "", "", "", ""),
		("PB","São Francisco","58818000","58818999","8","12", "", "", "", "", "", "", "", ""),
		("PB","Marizópolis","58819000","58819999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Lastro","58820000","58821999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Vieirópolis","58822000","58822999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Aparecida","58823000","58823999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Santa Cruz","58824000","58829999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Jericó","58830000","58831999","7","11", "", "", "", "", "", "", "", ""),
		("PB","Mato Grosso","58832000","58834999","8","12", "", "", "", "", "", "", "", ""),
		("PB","Lagoa","58835000","58839999","8","12", "", "", "", "", "", "", "", ""),
		("PB","Pombal","58840000","58852999","9","13", "", "", "", "", "", "", "", ""),
		("PB","São Domingos de Pombal","58853000","58854999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Cajazeirinhas","58855000","58856999","9","13", "", "", "", "", "", "", "", ""),
		("PB","São Bentinho","58857000","58859999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Paulista","58860000","58864999","9","13", "", "", "", "", "", "", "", ""),
		("PB","São Bento","58865000","58869999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Riacho dos Cavalos","58870000","58879999","13","17", "", "", "", "", "", "", "", ""),
		("PB","Brejo dos Santos","58880000","58883999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Catolé do Rocha","58884000","58886999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Bom Sucesso","58887000","58889999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Brejo do Cruz","58890000","58892999","5","9", "", "", "", "", "", "", "", ""),
		("PB","São José do Brejo do Cruz","58893000","58894999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Belém do Brejo do Cruz","58895000","58899999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Cajazeiras","58900000","58907999","3","13", "", "", "", "", "", "", "", ""),
		("PB","Poço de José de Moura","58908000","58909999","9","13", "", "", "", "", "", "", "", ""),
		("PB","São João do Rio do Peixe","58910000","58914999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Uiraúna","58915000","58919999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Triunfo","58920000","58921999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Bernardino Batista","58922000","58924999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Santa Helena","58925000","58927999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Santarém","58928000","58929999","8","12", "", "", "", "", "", "", "", ""),
		("PB","Bom Jesus","58930000","58932999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Poço Dantas","58933000","58934999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Cachoeira dos Índios","58935000","58939999","8","12", "", "", "", "", "", "", "", ""),
		("PB","São José de Piranhas","58940000","58944999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Carrapateira","58945000","58949999","8","12", "", "", "", "", "", "", "", ""),
		("PB","Monte Horebe","58950000","58954999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Serra Grande","58955000","58959999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Bonito de Santa Fé","58960000","58969999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Conceição","58970000","58977999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Santa Inês","58978000","58979999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Ibiara","58980000","58984999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Santana de Mangueira","58985000","58989999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Curral Velho","58990000","58992999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Boa Ventura","58993000","58993999","5","9", "", "", "", "", "", "", "", ""),
		("PB","Diamante","58994000","58994999","9","13", "", "", "", "", "", "", "", ""),
		("PB","Manaíra","58995000","58999999","9","13", "", "", "", "", "", "", "", ""),
		("RN","Natal","59000000","59139999","2","9", "", "", "", "", "", "", "", ""),
		("RN","Parnamirim","59140000","59161999","3","9", "", "", "", "", "", "", "", ""),
		("RN","São José de Mipibu","59162000","59163999","9","16", "", "", "", "", "", "", "", ""),
		("RN","Nísia Floresta","59164000","59167999","5","12", "", "", "", "", "", "", "", ""),
		("RN","Senador Georgino Avelino","59168000","59169999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Arez","59170000","59172999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Goianinha","59173000","59177999","10","17", "", "", "", "", "", "", "", ""),
		("RN","Tibau do Sul","59178000","59179999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Espírito Santo","59180000","59181999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Monte Alegre","59182000","59183999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Vera Cruz","59184000","59184999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Várzea","59185000","59187999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Jundiá","59188000","59189999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Canguaretama","59190000","59191999","6","13", "", "", "", "", "", "", "", ""),
		("RN","Vila Flor","59192000","59193999","6","13", "", "", "", "", "", "", "", ""),
		("RN","Baía Formosa","59194000","59195999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Pedro Velho","59196000","59197999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Montanhas","59198000","59199999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Santa Cruz","59200000","59209999","7","14", "", "", "", "", "", "", "", ""),
		("RN","São Bento do Trairi","59210000","59212999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Japi","59213000","59213999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Serra de São Bento","59214000","59214999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Nova Cruz","59215000","59216999","6","13", "", "", "", "", "", "", "", ""),
		("RN","Monte das Gameleiras","59217000","59217999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Passa e Fica","59218000","59218999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Brejinho","59219000","59219999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Coronel Ezequiel","59220000","59224999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Jaçanã","59225000","59226999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Lagoa D\'Anta","59227000","59229999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Campo Redondo","59230000","59234999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Lajes Pintadas","59235000","59239999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Tangará","59240000","59243999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Lagoa de Pedras","59244000","59244999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Serra Caiada","59245000","59246999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Lagoa Salgada","59247000","59249999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Senador Elói de Souza","59250000","59254999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Santo Antônio","59255000","59257999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Serrinha","59258000","59258999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Passagem","59259000","59259999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Boa Saúde","59260000","59269999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Bom Jesus","59270000","59274999","7","14", "", "", "", "", "", "", "", ""),
		("RN","São José do Campestre","59275000","59279999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Macaíba","59280000","59289999","9","16", "", "", "", "", "", "", "", ""),
		("RN","São Gonçalo do Amarante","59290000","59299999","5","12", "", "", "", "", "", "", "", ""),
		("RN","Caicó","59300000","59309999","6","13", "", "", "", "", "", "", "", ""),
		("RN","São João do Sabugi","59310000","59314999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Ipueira","59315000","59317999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Serra Negra do Norte","59318000","59319999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Timbaúba dos Batistas","59320000","59323999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Jardim de Piranhas","59324000","59326999","6","13", "", "", "", "", "", "", "", ""),
		("RN","São Fernando","59327000","59329999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Jucurutu","59330000","59334999","11","18", "", "", "", "", "", "", "", ""),
		("RN","Florânia","59335000","59337999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Tenente Laurentino Cruz","59338000","59339999","7","14", "", "", "", "", "", "", "", ""),
		("RN","São Vicente","59340000","59342999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Jardim do Seridó","59343000","59346999","10","17", "", "", "", "", "", "", "", ""),
		("RN","Ouro Branco","59347000","59349999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Santana do Seridó","59350000","59354999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Equador","59355000","59359999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Parelhas","59360000","59369999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Acari","59370000","59373999","6","13", "", "", "", "", "", "", "", ""),
		("RN","Carnaúba dos Dantas","59374000","59374999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Cruzeta","59375000","59377999","11","18", "", "", "", "", "", "", "", ""),
		("RN","São José do Seridó","59378000","59379999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Currais Novos","59380000","59389999","9","16", "", "", "", "", "", "", "", ""),
		("RN","Lagoa Nova","59390000","59394999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Cerro Corá","59395000","59399999","7","14", "", "", "", "", "", "", "", ""),
		("RN","São Tomé","59400000","59409999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Barcelona","59410000","59419999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Ruy Barbosa","59420000","59429999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Lagoa de Velhos","59430000","59439999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Sítio Novo","59440000","59459999","7","14", "", "", "", "", "", "", "", ""),
		("RN","São Paulo do Potengi","59460000","59463999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Santa Maria","59464000","59469999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Riachuelo","59470000","59479999","7","14", "", "", "", "", "", "", "", ""),
		("RN","São Pedro","59480000","59489999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Ielmo Marinho","59490000","59499999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Macau","59500000","59503999","9","16", "", "", "", "", "", "", "", ""),
		("RN","Pendências","59504000","59506999","9","16", "", "", "", "", "", "", "", ""),
		("RN","Alto do Rodrigues","59507000","59507999","9","16", "", "", "", "", "", "", "", ""),
		("RN","Ipanguaçu","59508000","59509999","5","12", "", "", "", "", "", "", "", ""),
		("RN","Afonso Bezerra","59510000","59512999","9","16", "", "", "", "", "", "", "", ""),
		("RN","Itajá","59513000","59514999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Angicos","59515000","59516999","6","13", "", "", "", "", "", "", "", ""),
		("RN","Fernando Pedroza","59517000","59517999","7","14", "", "", "", "", "", "", "", ""),
		("RN","São Rafael","59518000","59519999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Santana do Matos","59520000","59527999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Bodó","59528000","59529999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Pedro Avelino","59530000","59534999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Lajes","59535000","59539999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Caiçara do Rio do Vento","59540000","59543999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Jardim de Angicos","59544000","59546999","6","13", "", "", "", "", "", "", "", ""),
		("RN","Pedra Preta","59547000","59549999","7","14", "", "", "", "", "", "", "", ""),
		("RN","João Câmara","59550000","59554999","10","17", "", "", "", "", "", "", "", ""),
		("RN","Bento Fernandes","59555000","59559999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Poço Branco","59560000","59564999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Taipu","59565000","59569999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Ceará-Mirim","59570000","59574999","10","17", "", "", "", "", "", "", "", ""),
		("RN","Extremoz","59575000","59577999","9","16", "", "", "", "", "", "", "", ""),
		("RN","Rio do Fogo","59578000","59579999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Maxaranguape","59580000","59581999","6","13", "", "", "", "", "", "", "", ""),
		("RN","Pureza","59582000","59583999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Touros","59584000","59584999","7","14", "", "", "", "", "", "", "", ""),
		("RN","São Miguel de Touros","59585000","59585999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Parazinho","59586000","59587999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Pedra Grande","59588000","59589999","7","14", "", "", "", "", "", "", "", ""),
		("RN","São Bento do Norte","59590000","59591999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Caiçara do Norte","59592000","59593999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Jandaíra","59594000","59595999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Galinhos","59596000","59597999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Guamaré","59598000","59599999","9","16", "", "", "", "", "", "", "", ""),
		("RN","Mossoró","59600000","59649999","3","10", "", "", "", "", "", "", "", ""),
		("RN","Açu","59650000","59654999","9","16", "", "", "", "", "", "", "", ""),
		("RN","Areia Branca","59655000","59659999","9","16", "", "", "", "", "", "", "", ""),
		("RN","Paraú","59660000","59662999","5","12", "", "", "", "", "", "", "", ""),
		("RN","Serra do Mel","59663000","59664999","5","12", "", "", "", "", "", "", "", ""),
		("RN","Carnaubais","59665000","59667999","5","12", "", "", "", "", "", "", "", ""),
		("RN","Porto do Mangue","59668000","59669999","5","12", "", "", "", "", "", "", "", ""),
		("RN","Upanema","59670000","59674999","5","12", "", "", "", "", "", "", "", ""),
		("RN","Grossos","59675000","59677999","9","16", "", "", "", "", "", "", "", ""),
		("RN","Tibau","59678000","59679999","5","12", "", "", "", "", "", "", "", ""),
		("RN","Campo Grande","59680000","59684999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Triunfo Potiguar","59685000","59689999","5","12", "", "", "", "", "", "", "", ""),
		("RN","Janduís","59690000","59694999","5","12", "", "", "", "", "", "", "", ""),
		("RN","Baraúna","59695000","59699999","5","12", "", "", "", "", "", "", "", ""),
		("RN","Apodi","59700000","59729999","9","16", "", "", "", "", "", "", "", ""),
		("RN","Olho-D\'Água do Borges","59730000","59739999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Rafael Godeiro","59740000","59759999","11","18", "", "", "", "", "", "", "", ""),
		("RN","Almino Afonso","59760000","59769999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Patu","59770000","59774999","11","18", "", "", "", "", "", "", "", ""),
		("RN","Messias Targino","59775000","59779999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Caraúbas","59780000","59789999","5","12", "", "", "", "", "", "", "", ""),
		("RN","Governador Dix-Sept Rosado","59790000","59794999","5","12", "", "", "", "", "", "", "", ""),
		("RN","Felipe Guerra","59795000","59799999","9","16", "", "", "", "", "", "", "", ""),
		("RN","Martins","59800000","59804999","5","12", "", "", "", "", "", "", "", ""),
		("RN","Lucrécia","59805000","59807999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Serrinha dos Pintos","59808000","59809999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Portalegre","59810000","59814999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Viçosa","59815000","59819999","6","13", "", "", "", "", "", "", "", ""),
		("RN","Riacho da Cruz","59820000","59829999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Rodolfo Fernandes","59830000","59839999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Taboleiro Grande","59840000","59854999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Itaú","59855000","59855999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Severiano Melo","59856000","59864999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Umarizal","59865000","59869999","6","13", "", "", "", "", "", "", "", ""),
		("RN","Antônio Martins","59870000","59879999","7","14", "", "", "", "", "", "", "", ""),
		("RN","João Dias","59880000","59889999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Frutuoso Gomes","59890000","59899999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Pau dos Ferros","59900000","59901999","9","16", "", "", "", "", "", "", "", ""),
		("RN","Francisco Dantas","59902000","59904999","11","18", "", "", "", "", "", "", "", ""),
		("RN","Encanto","59905000","59907999","7","14", "", "", "", "", "", "", "", ""),
		("RN","São Francisco do Oeste","59908000","59909999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Doutor Severiano","59910000","59919999","7","14", "", "", "", "", "", "", "", ""),
		("RN","São Miguel","59920000","59924999","11","18", "", "", "", "", "", "", "", ""),
		("RN","Venha-Ver","59925000","59929999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Coronel João Pessoa","59930000","59939999","7","14", "", "", "", "", "", "", "", ""),
		("RN","Luís Gomes","59940000","59944999","11","18", "", "", "", "", "", "", "", "");
' );

$installer->endSetup();