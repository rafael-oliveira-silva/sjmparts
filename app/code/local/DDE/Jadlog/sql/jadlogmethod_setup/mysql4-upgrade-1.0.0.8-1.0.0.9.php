﻿<?php

$installer = $this;

$installer->startSetup();

$installer->run('
	INSERT INTO '.$installer->getTable('jadlogmethod/cep').'( estado, localidade, cep_inicial, cep_final, standard, rodoviario, package, economico, doc, corporate, com, internacional, cargo, emergencia ) VALUES("BA","Alcobaça","45990000","45994999","6","9", "", "", "", "", "", "", "", ""),
		("BA","Teixeira de Freitas","45995000","45998999","5","7", "", "", "", "", "", "", "", ""),
		("BA","Brumado","46100000","46109999","8","12", "", "", "", "", "", "", "", ""),
		("BA","Malhada de Pedras","46110000","46129999","6","10", "", "", "", "", "", "", "", ""),
		("BA","Aracatu","46130000","46139999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Livramento de Nossa Senhora","46140000","46164999","4","8", "", "", "", "", "", "", "", ""),
		("BA","Dom Basílio","46165000","46169999","4","8", "", "", "", "", "", "", "", ""),
		("BA","Rio de Contas","46170000","46179999","4","8", "", "", "", "", "", "", "", ""),
		("BA","Érico Cardoso","46180000","46189999","9","13", "", "", "", "", "", "", "", ""),
		("BA","Paramirim","46190000","46199999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Condeúba","46200000","46204999","9","13", "", "", "", "", "", "", "", ""),
		("BA","Guajeru","46205000","46219999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Rio do Antônio","46220000","46249999","6","10", "", "", "", "", "", "", "", ""),
		("BA","Presidente Jânio Quadros","46250000","46254999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Maetinga","46255000","46269999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Piripá","46270000","46279999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Cordeiros","46280000","46289999","9","13", "", "", "", "", "", "", "", ""),
		("BA","Mortugaba","46290000","46299999","7","11", "", "", "", "", "", "", "", ""),
		("BA","Caculé","46300000","46309999","7","11", "", "", "", "", "", "", "", ""),
		("BA","Jacaraci","46310000","46329999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Licínio de Almeida","46330000","46349999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Urandi","46350000","46359999","4","8", "", "", "", "", "", "", "", ""),
		("BA","Pindaí","46360000","46379999","3","7", "", "", "", "", "", "", "", ""),
		("BA","Candiba","46380000","46389999","3","7", "", "", "", "", "", "", "", ""),
		("BA","Ibiassucê","46390000","46399999","8","12", "", "", "", "", "", "", "", ""),
		("BA","Caetité","46400000","46424999","3","7", "", "", "", "", "", "", "", ""),
		("BA","Lagoa Real","46425000","46429999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Guanambi","46430000","46437999","2","7", "", "", "", "", "", "", "", ""),
		("BA","Iuiu","46438000","46439999","4","8", "", "", "", "", "", "", "", ""),
		("BA","Malhada","46440000","46444999","4","8", "", "", "", "", "", "", "", ""),
		("BA","Carinhanha","46445000","46445999","7","11", "", "", "", "", "", "", "", ""),
		("BA","Feira da Mata","46446000","46449999","6","10", "", "", "", "", "", "", "", ""),
		("BA","Sebastião Laranjeiras","46450000","46459999","4","8", "", "", "", "", "", "", "", ""),
		("BA","Palmas de Monte Alto","46460000","46469999","3","7", "", "", "", "", "", "", "", ""),
		("BA","Riacho de Santana","46470000","46479999","7","11", "", "", "", "", "", "", "", ""),
		("BA","Matina","46480000","46489999","3","7", "", "", "", "", "", "", "", ""),
		("BA","Igaporã","46490000","46499999","8","12", "", "", "", "", "", "", "", ""),
		("BA","Macaúbas","46500000","46529999","9","13", "", "", "", "", "", "", "", ""),
		("BA","Boquira","46530000","46539999","10","14", "", "", "", "", "", "", "", ""),
		("BA","Ibipitanga","46540000","46549999","9","13", "", "", "", "", "", "", "", ""),
		("BA","Rio do Pires","46550000","46569999","9","13", "", "", "", "", "", "", "", ""),
		("BA","Botuporã","46570000","46574999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Caturama","46575000","46579999","6","10", "", "", "", "", "", "", "", ""),
		("BA","Tanque Novo","46580000","46599999","8","12", "", "", "", "", "", "", "", ""),
		("BA","Tanhaçu","46600000","46619999","4","8", "", "", "", "", "", "", "", ""),
		("BA","Contendas do Sincorá","46620000","46639999","7","11", "", "", "", "", "", "", "", ""),
		("BA","Ituaçu","46640000","46649999","10","14", "", "", "", "", "", "", "", ""),
		("BA","Barra da Estiva","46650000","46669999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Jussiape","46670000","46689999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Abaíra","46690000","46699999","9","13", "", "", "", "", "", "", "", ""),
		("BA","Ibitiara","46700000","46729999","9","13", "", "", "", "", "", "", "", ""),
		("BA","Novo Horizonte","46730000","46739999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Boninal","46740000","46749999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Mucugê","46750000","46759999","9","13", "", "", "", "", "", "", "", ""),
		("BA","Ibicoara","46760000","46764999","9","13", "", "", "", "", "", "", "", ""),
		("BA","Piatã","46765000","46769999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Iramaia","46770000","46779999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Marcionílio Souza","46780000","46789999","7","10", "", "", "", "", "", "", "", ""),
		("BA","Itaeté","46790000","46799999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Ruy Barbosa","46800000","46804999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Macajuba","46805000","46809999","9","13", "", "", "", "", "", "", "", ""),
		("BA","Utinga","46810000","46819999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Bonito","46820000","46824999","9","13", "", "", "", "", "", "", "", ""),
		("BA","Lajedinho","46825000","46829999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Andaraí","46830000","46834999","9","13", "", "", "", "", "", "", "", ""),
		("BA","Nova Redenção","46835000","46839999","9","13", "", "", "", "", "", "", "", ""),
		("BA","Ibiquera","46840000","46849999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Boa Vista do Tupim","46850000","46859999","9","13", "", "", "", "", "", "", "", ""),
		("BA","Iaçu","46860000","46874999","7","9", "", "", "", "", "", "", "", ""),
		("BA","Itatim","46875000","46879999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Itaberaba","46880000","46899999","9","13", "", "", "", "", "", "", "", ""),
		("BA","Seabra","46900000","46929999","9","13", "", "", "", "", "", "", "", ""),
		("BA","Palmeiras","46930000","46959999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Lençóis","46960000","46969999","9","13", "", "", "", "", "", "", "", ""),
		("BA","Wagner","46970000","46979999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Iraquara","46980000","46989999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Souto Soares","46990000","47099999","9","13", "", "", "", "", "", "", "", ""),
		("BA","Barra","47100000","47114999","12","17", "", "", "", "", "", "", "", ""),
		("BA","Muquém de São Francisco","47115000","47119999","10","17", "", "", "", "", "", "", "", ""),
		("BA","Buritirama","47120000","47149999","12","17", "", "", "", "", "", "", "", ""),
		("BA","Santa Rita de Cássia","47150000","47159999","8","12", "", "", "", "", "", "", "", ""),
		("BA","Mansidão","47160000","47199999","10","17", "", "", "", "", "", "", "", ""),
		("BA","Remanso","47200000","47219999","9","13", "", "", "", "", "", "", "", ""),
		("BA","Campo Alegre de Lourdes","47220000","47239999","9","15", "", "", "", "", "", "", "", ""),
		("BA","Pilão Arcado","47240000","47299999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Casa Nova","47300000","47349999","9","13", "", "", "", "", "", "", "", ""),
		("BA","Sento Sé","47350000","47399999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Xique-Xique","47400000","47439999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Itaguaçu da Bahia","47440000","47449999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Gentio do Ouro","47450000","47499999","9","13", "", "", "", "", "", "", "", ""),
		("BA","Paratinga","47500000","47519999","6","10", "", "", "", "", "", "", "", ""),
		("BA","Ibotirama","47520000","47529999","9","13", "", "", "", "", "", "", "", ""),
		("BA","Oliveira dos Brejinhos","47530000","47559999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Brotas de Macaúbas","47560000","47579999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Morpará","47580000","47589999","6","10", "", "", "", "", "", "", "", ""),
		("BA","Ipupiara","47590000","47599999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Bom Jesus da Lapa","47600000","47609999","7","11", "", "", "", "", "", "", "", ""),
		("BA","Sítio do Mato","47610000","47629999","6","10", "", "", "", "", "", "", "", ""),
		("BA","Serra do Ramalho","47630000","47639999","9","13", "", "", "", "", "", "", "", ""),
		("BA","Santa Maria da Vitória","47640000","47649999","12","17", "", "", "", "", "", "", "", ""),
		("BA","Correntina","47650000","47654999","8","12", "", "", "", "", "", "", "", ""),
		("BA","Jaborandi","47655000","47664999","12","17", "", "", "", "", "", "", "", ""),
		("BA","São Félix do Coribe","47665000","47679999","4","8", "", "", "", "", "", "", "", ""),
		("BA","Cocos","47680000","47689999","10","14", "", "", "", "", "", "", "", ""),
		("BA","Coribe","47690000","47699999","6","10", "", "", "", "", "", "", "", ""),
		("BA","Santana","47700000","47729999","12","17", "", "", "", "", "", "", "", ""),
		("BA","Canápolis","47730000","47739999","8","12", "", "", "", "", "", "", "", ""),
		("BA","Serra Dourada","47740000","47749999","12","17", "", "", "", "", "", "", "", ""),
		("BA","Brejolândia","47750000","47759999","10","15", "", "", "", "", "", "", "", ""),
		("BA","Tabocas do Brejo Velho","47760000","47799999","12","17", "", "", "", "", "", "", "", ""),
		("BA","Barreiras","47800000","47809999","2","10", "", "", "", "", "", "", "", ""),
		("BA","Catolândia","47815000","47819999","6","12", "", "", "", "", "", "", "", ""),
		("BA","São Desidério","47820000","47829999","6","10", "", "", "", "", "", "", "", ""),
		("BA","Baianópolis","47830000","47849999","8","12", "", "", "", "", "", "", "", ""),
		("BA","Luís Eduardo Magalhães","47850000","47899999","3","11", "", "", "", "", "", "", "", ""),
		("BA","Cotegipe","47900000","47939999","6","12", "", "", "", "", "", "", "", ""),
		("BA","Wanderley","47940000","47949999","8","12", "", "", "", "", "", "", "", ""),
		("BA","Cristópolis","47950000","47959999","6","12", "", "", "", "", "", "", "", ""),
		("BA","Angical","47960000","47969999","8","12", "", "", "", "", "", "", "", ""),
		("BA","Riachão das Neves","47970000","47989999","6","12", "", "", "", "", "", "", "", ""),
		("BA","Formosa do Rio Preto","47990000","47999999","7","12", "", "", "", "", "", "", "", ""),
		("BA","Alagoinhas","48000000","48099999","8","12", "", "", "", "", "", "", "", ""),
		("BA","Araçás","48108000","48109999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Catu","48110000","48119999","11","16", "", "", "", "", "", "", "", ""),
		("BA","Pojuca","48120000","48129999","8","12", "", "", "", "", "", "", "", ""),
		("BA","Aramari","48130000","48139999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Pedrão","48140000","48149999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Ouriçangas","48150000","48169999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Água Fria","48170000","48179999","6","10", "", "", "", "", "", "", "", ""),
		("BA","Entre Rios","48180000","48279999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Mata de São João","48280000","48289999","6","11", "", "", "", "", "", "", "", ""),
		("BA","Itanagra","48290000","48299999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Conde","48300000","48309999","8","12", "", "", "", "", "", "", "", ""),
		("BA","Jandaíra","48310000","48329999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Rio Real","48330000","48349999","9","13", "", "", "", "", "", "", "", ""),
		("BA","Aporá","48350000","48359999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Acajutiba","48360000","48369999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Esplanada","48370000","48389999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Cardeal da Silva","48390000","48399999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Ribeira do Pombal","48400000","48404999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Banzaê","48405000","48409999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Cícero Dantas","48410000","48414999","6","10", "", "", "", "", "", "", "", ""),
		("BA","Fátima","48415000","48419999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Antas","48420000","48429999","6","10", "", "", "", "", "", "", "", ""),
		("BA","Paripiranga","48430000","48434999","9","13", "", "", "", "", "", "", "", ""),
		("BA","Adustina","48435000","48439999","10","14", "", "", "", "", "", "", "", ""),
		("BA","Ribeira do Amparo","48440000","48444999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Heliópolis","48445000","48449999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Cipó","48450000","48454999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Novo Triunfo","48455000","48459999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Nova Soure","48460000","48469999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Olindina","48470000","48474999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Itapicuru","48475000","48479999","9","13", "", "", "", "", "", "", "", ""),
		("BA","Crisópolis","48480000","48484999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Sátiro Dias","48485000","48489999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Inhambupe","48490000","48499999","9","13", "", "", "", "", "", "", "", ""),
		("BA","Euclides da Cunha","48500000","48519999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Canudos","48520000","48539999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Jeremoabo","48540000","48564999","4","8", "", "", "", "", "", "", "", ""),
		("BA","Sítio do Quinto","48565000","48569999","9","13", "", "", "", "", "", "", "", ""),
		("BA","Santa Brígida","48570000","48579999","8","12", "", "", "", "", "", "", "", ""),
		("BA","Pedro Alexandre","48580000","48589999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Coronel João Sá","48590000","48599999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Paulo Afonso","48600000","48609999","2","7", "", "", "", "", "", "", "", ""),
		("BA","Glória","48610000","48629999","4","8", "", "", "", "", "", "", "", ""),
		("BA","Rodelas","48630000","48649999","9","13", "", "", "", "", "", "", "", ""),
		("BA","Macururé","48650000","48659999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Chorrochó","48660000","48679999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Abaré","48680000","48699999","9","13", "", "", "", "", "", "", "", ""),
		("BA","Serrinha","48700000","48704999","4","8", "", "", "", "", "", "", "", ""),
		("BA","Barrocas","48705000","48709999","6","10", "", "", "", "", "", "", "", ""),
		("BA","Candeal","48710000","48719999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Lamarão","48720000","48724999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Ichu","48725000","48729999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Conceição do Coité","48730000","48749999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Retirolândia","48750000","48759999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Araci","48760000","48769999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Teofilândia","48770000","48779999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Biritinga","48780000","48789999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Tucano","48790000","48799999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Monte Santo","48800000","48829999","9","13", "", "", "", "", "", "", "", ""),
		("BA","Quijingue","48830000","48839999","9","13", "", "", "", "", "", "", "", ""),
		("BA","Cansanção","48840000","48849999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Itiúba","48850000","48859999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Queimadas","48860000","48869999","9","13", "", "", "", "", "", "", "", ""),
		("BA","Nordestina","48870000","48879999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Santaluz","48880000","48889999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Valente","48890000","48894999","5","9", "", "", "", "", "", "", "", ""),
		("BA","São Domingos","48895000","48899999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Juazeiro","48900000","48909999","4","7", "", "", "", "", "", "", "", ""),
		("BA","Sobradinho","48925000","48929999","4","8", "", "", "", "", "", "", "", ""),
		("BA","Curaçá","48930000","48949999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Uauá","48950000","48959999","9","13", "", "", "", "", "", "", "", ""),
		("BA","Jaguarari","48960000","48966999","4","8", "", "", "", "", "", "", "", ""),
		("BA","Núcleo Residencial Pilar (Jaguarari)","48967000","48969999","5","9", "", "", "", "", "", "", "", ""),
		("BA","Senhor do Bonfim","48970000","48989999","8","12", "", "", "", "", "", "", "", ""),
		("BA","Andorinha","48990000","48999999","5","9", "", "", "", "", "", "", "", ""),
		("SE","Aracaju","49000000","49098999","2","5", "", "", "", "", "", "", "", ""),
		("SE","São Cristóvão","49100000","49119999","4","5", "", "", "", "", "", "", "", ""),
		("SE","Itaporanga D\'Ajuda","49120000","49129999","4","5", "", "", "", "", "", "", "", ""),
		("SE","Riachuelo","49130000","49139999","4","5", "", "", "", "", "", "", "", ""),
		("SE","Barra dos Coqueiros","49140000","49159999","4","5", "", "", "", "", "", "", "", ""),
		("SE","Nossa Senhora do Socorro","49160000","49169999","4","5", "", "", "", "", "", "", "", ""),
		("SE","Laranjeiras","49170000","49179999","4","7", "", "", "", "", "", "", "", ""),
		("SE","Santo Amaro das Brotas","49180000","49189999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Pirambu","49190000","49199999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Estância","49200000","49219999","8","11", "", "", "", "", "", "", "", ""),
		("SE","Arauá","49220000","49229999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Santa Luzia do Itanhy","49230000","49249999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Indiaroba","49250000","49259999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Umbaúba","49260000","49269999","4","7", "", "", "", "", "", "", "", ""),
		("SE","Cristinápolis","49270000","49279999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Tomar do Geru","49280000","49289999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Itabaianinha","49290000","49299999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Tobias Barreto","49300000","49319999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Riachão do Dantas","49320000","49349999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Pedrinhas","49350000","49359999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Boquim","49360000","49389999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Salgado","49390000","49399999","4","7", "", "", "", "", "", "", "", ""),
		("SE","Lagarto","49400000","49479999","4","7", "", "", "", "", "", "", "", ""),
		("SE","Simão Dias","49480000","49489999","4","7", "", "", "", "", "", "", "", ""),
		("SE","Poço Verde","49490000","49499999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Itabaiana","49500000","49511999","4","7", "", "", "", "", "", "", "", ""),
		("SE","Pedra Mole","49512000","49513999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Frei Paulo","49514000","49516999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Pinhão","49517000","49519999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Campo do Brito","49520000","49524999","5","8", "", "", "", "", "", "", "", ""),
		("SE","São Domingos","49525000","49529999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Ribeirópolis","49530000","49534999","4","7", "", "", "", "", "", "", "", ""),
		("SE","São Miguel do Aleixo","49535000","49539999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Nossa Senhora Aparecida","49540000","49549999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Carira","49550000","49559999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Moita Bonita","49560000","49564999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Macambira","49565000","49569999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Malhador","49570000","49579999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Areia Branca","49580000","49599999","4","7", "", "", "", "", "", "", "", ""),
		("SE","Nossa Senhora das Dores","49600000","49629999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Siriri","49630000","49639999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Santa Rosa de Lima","49640000","49649999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Divina Pastora","49650000","49659999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Cumbe","49660000","49669999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Feira Nova","49670000","49679999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Nossa Senhora da Glória","49680000","49689999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Monte Alegre de Sergipe","49690000","49699999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Capela","49700000","49739999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Carmópolis","49740000","49749999","4","7", "", "", "", "", "", "", "", ""),
		("SE","General Maynard","49750000","49759999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Rosário do Catete","49760000","49769999","8","11", "", "", "", "", "", "", "", ""),
		("SE","Maruim","49770000","49779999","4","7", "", "", "", "", "", "", "", ""),
		("SE","Muribeca","49780000","49789999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Aquidabã","49790000","49799999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Porto da Folha","49800000","49809999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Poço Redondo","49810000","49819999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Canindé de São Francisco","49820000","49829999","9","12", "", "", "", "", "", "", "", ""),
		("SE","Gararu","49830000","49859999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Graccho Cardoso","49860000","49869999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Itabi","49870000","49879999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Canhoba","49880000","49889999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Nossa Senhora de Lourdes","49890000","49899999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Propriá","49900000","49909999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Telha","49910000","49919999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Amparo de São Francisco","49920000","49929999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Cedro de São João","49930000","49939999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Malhada dos Bois","49940000","49944999","5","8", "", "", "", "", "", "", "", ""),
		("SE","São Francisco","49945000","49949999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Japoatã","49950000","49959999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Japaratuba","49960000","49969999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Pacatuba","49970000","49979999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Neópolis","49980000","49984999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Santana do São Francisco","49985000","49989999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Ilha das Flores","49990000","49994999","5","8", "", "", "", "", "", "", "", ""),
		("SE","Brejo Grande","49995000","49999999","5","8", "", "", "", "", "", "", "", ""),
		("PE","Recife","50000000","52999999","1","7", "", "", "", "", "", "", "", ""),
		("PE","Olinda","53000000","53399999","2","7", "", "", "", "", "", "", "", ""),
		("PE","Paulista","53400000","53499999","2","7", "", "", "", "", "", "", "", ""),
		("PE","Abreu e Lima","53500000","53599999","2","7", "", "", "", "", "", "", "", ""),
		("PE","Igarassu","53600000","53659999","2","7", "", "", "", "", "", "", "", ""),
		("PE","Araçoiaba","53690000","53699999","3","7", "", "", "", "", "", "", "", ""),
		("PE","Itapissuma","53700000","53899999","4","8", "", "", "", "", "", "", "", ""),
		("PE","Ilha de Itamaracá","53900000","53989999","4","8", "", "", "", "", "", "", "", ""),
		("PE","Fernando de Noronha","53990000","53999999","11","17", "", "", "", "", "", "", "", ""),
		("PE","Jaboatão dos Guararapes","54000000","54499999","1","9", "", "", "", "", "", "", "", ""),
		("PE","Cabo de Santo Agostinho","54500000","54589999","3","9", "", "", "", "", "", "", "", ""),
		("PE","São Lourenço da Mata","54700000","54748999","7","13", "", "", "", "", "", "", "", ""),
		("PE","Camaragibe","54750000","54799999","3","7", "", "", "", "", "", "", "", ""),
		("PE","Moreno","54800000","54999999","7","11", "", "", "", "", "", "", "", ""),
		("PE","Caruaru","55000000","55099999","3","7", "", "", "", "", "", "", "", ""),
		("PE","Riacho das Almas","55120000","55124999","5","11", "", "", "", "", "", "", "", ""),
		("PE","Toritama","55125000","55129999","5","11", "", "", "", "", "", "", "", ""),
		("PE","São Caitano","55130000","55139999","5","11", "", "", "", "", "", "", "", ""),
		("PE","Tacaimbó","55140000","55149999","5","11", "", "", "", "", "", "", "", ""),
		("PE","Belo Jardim","55150000","55159999","9","13", "", "", "", "", "", "", "", ""),
		("PE","Brejo da Madre de Deus","55170000","55179999","5","11", "", "", "", "", "", "", "", ""),
		("PE","Jataúba","55180000","55189999","5","11", "", "", "", "", "", "", "", "");
' );

$installer->endSetup();