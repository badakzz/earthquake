Earthquake est un projet d'alarme connectée. Il utilise une carte Arduino WiFi et des moteurs (on peut en ajouter autant qu'on veut pour tout faire trembler).

L'alarme est paramétrable via une application web développée en PHP. Cette dernière permet à l'utilisateur de s'authentifier (les utilisateurs sont stockés dans une base de donnée MariaDB SQL), puis de mettre au point une alarme, la remplacer, de l'annuler, ou si elle vibre de la faire s'arreter.

L'application web et la carte Arduino communiquent via des requêtes HTTP :
-  Un identifiant unique est attribué à l'utilisateur au moment de son inscription.
-  L'utilisateur paramètre l'heure de son alarme, ce qui modifie en base de donnée la valeur du timestamp qui lui est associé.
- L'alarme est constamment en train d'envoyer des requêtes HTTP GET à la webapp afin de recuperer un payload JSON. Celui-ci est encodé via une requête SQL à la base de données, et comprend un identifiant et un timestamp. Selon l'identifiant, l'alarme itere sur le payload et récupère le timestamp associé.
- Selon le timestamp, l'alarme comprend que l'alarme est soit programmée, soit ne l'est pas, soit doit se mettre à vibrer.
- Quand l'alarme vibre, elle envoie à la webapp une requête HTTP POST comprenant l'identifiant de l'utilisateur et un timestamp de valeur 0. La webapp l’interprète alors et envoie une requête SQL pour mettre à jour la valeur du timestamp liée à l'utilisateur en base de donnée. De ce fait, l'utilisateur a la possibilité sur l'interface vue de la webapp d’éteindre son alarme.

De base, si l'utilisateur se connecte à la webapp sur son téléphone mobile ou sur tablette, il n'aura pas la possibilité d’éteindre son alarme quand elle vibre. Il devra se lever et se connecter depuis un ordinateur pour ce faire. Cette option est paramétrable dans le profil de l'utilisateur.

![alt text](https://imgur.com/QBISImy)

![image](https://user-images.githubusercontent.com/81807525/181739157-f6299435-1780-4e55-9852-2ea768584cdc.png)

![image](https://user-images.githubusercontent.com/81807525/181744171-70041e57-4156-4ace-9db4-d7cf0b20eda3.png)

![image](https://user-images.githubusercontent.com/81807525/181739306-8862dc6b-dae0-4b41-a37e-1e9de8f6335d.png)

![image](https://user-images.githubusercontent.com/81807525/181744380-7f7cf923-9932-4c42-9ba3-bf89f8beb951.png)

![image](https://user-images.githubusercontent.com/81807525/181744502-44650264-1315-4ff3-9028-5ab45eacb4ea.png)


Source code for the back-end logic of the app.

Arduino code can be found here : https://gist.github.com/badakzz/73c6ec73338badb5bf67a439220819c8#file-vibratingmotoralarm-ino
