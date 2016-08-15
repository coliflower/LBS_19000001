# LBS_19000001
Edomi | iTunes | Server

# LBS create / edit / rules ...

* EDOMI-Wiki: http://www.knx-home.net/wiki/index.php?title=Logic

# Installation

* EDOMI-Wiki: http://www.knx-home.net/wiki/index.php?title=H%C3%A4ufig_gestellte_Fragen#Wie_installiere_ich_Logikbausteine.3F

# Help

* EDOMI: http://www.edomi.de
* EDOMI-Support: https://knx-user-forum.de/forum/projektforen/edomi
* EDOMI-Wiki: http://www.knx-home.net/wiki/index.php?title=Hauptseite

# Beschreibung

<dl>
<dt>Purpose ...</dt>
<dd>
To be abele to communicate between Edomi-Server and iTunes.<br>
In that current version only information receiving (from iTunes) is possible.<br>
After implementation of UDP-lstener in Tune-Insturctor, sending of commands to iTunes will be possible, too.<br>
Other version of LBS19000xxx is able to communicate between Edomi-Server and iTunes via Apple-Scripts, only.<br>
Beside of that LBS (Server), other LBS are needed: "<b>player</b>", "<b>track</b>" and "<b>airplay</b>" - otional "<b>playlist</b>" and "<b>eq</b>".
</dd>

<dt>Needed files ...</dt>
<dd>
Apache-WebServer am Mac aktivieren und konfigurieren ... Mac-User ist das Zielverzeichnis.
Apple-Skripte - das Installationspaket ist unter folgendem Link zu finden: http://ipfelgruen.de/forum/software/skripte/eigene-tools-und-gadgets/2283-itunes-und-airplay-über-webserver-steuern
Ursprünglich wurden die Skripte für den GIRA-HS4 und EibPC entwickelt/angepasst (Senden/Empfangen) - diser LBS 19000001 kann die Skripte zur Steuerung von iTunes verwenden,
die Implementierung wird im LBS "Player" umgesetzt, als Rückmeldung werden die JSON-Dateien benötigt.
Hier auch ein Danke an Michael für die großartige Unterstützung und Perfektionierung der Aplle-Skripte sowie der einfachen Installationsroutine.
Für die Generierung benötigte JSON-Dateien bitte entweder eine individuelle Lösung entwickeln ODER den Tune?Instructor installieren (www.tune-instructor.de).
An dieser Stelle noch mein Dank an Tibor, den Entwickler von Tune?Instructor der das EXPORT (JSON-Dateien) implementiert hat.
Beschreibung für die Konfiguration von Tune?Instructor liegt in der 19000001_lbs.ZIP-Datei im Downloadbereich.
</dd>

<dt>Inputs ...</dt>
<dd>
<b>E1</b>: TRIGGER: system-time (KO 5) = 1 secound, other trigger if needed.<br>
<b>E2</b>: DEBUG: standard = 4 (includes "emergency", "alert", "critical" and "error".<br>
<b>E3</b>: IP-ASDRESS: the target address (IP) of the server where the files (.JSON) are stored (Mac itself, Edomi-, or other web- or file-server).<br>
<b>E4</b>: PATH: the path were the files are saved.<br>
<b>E5</b>: USER-name on Mac if the needed files are saved on Mac (especially if the web-server is on Mac in standard-path /sites).<br>
<b>E10</b>: MODUS: http OR file (depends whether the files are storred on a web- or file-server).<br>
<b>E11</b>: NAME of the JSON-file of "player".<br>
<b>E12</b>: NAME of the JSON-file of "airplay".<br>
<b>E13</b>: NAME of the JSON-file of "playlists".<br>
<b>E14</b>: NAME of the JSON-file of "eq".<br>
<b>E15</b>: NAME of the JSON-file of "track".<br>
</dd>

<dt>Outpunts ...</dt>
<dd>
<b>A1</b>: LINK: bei Modus FILE ein SHARE und bei Modus HTTP ein URL.<br>
<b>A10</b>: MODUS: darf nicht leer sein - entweder "file" oder "http".<br>
<b>A11</b>: JSON-String für den LBS "Player".<br>
<b>A12</b>: JSON-String für den LBS "Airplays".<br>
<b>A13</b>: JSON-String für den LBS "Playlists".<br>
<b>A14</b>: JSON-String für den LBS "eq".<br>
<b>A15</b>: JSON-String für den LBS "Track".<br>
</dd>

<dt>http/file ...</dt>
<dd>
<b>E10</b>: if "file" than access to .JSON + .JPG via NFS-share in EDOMI (share need to be set up in advance).<br>
<b>E10</b>: if "http" than access to .JSON + .JPG via WEB-share (server) on Mac (Apache).<br>
</dd>

<dt>Logik ...</dt>
<dd>
blablablablabla ... see ...
</dd>

<dt>Log-Level ...</dt>
<dd>
If you choise a log-level, than there will be only log-messages in the log-centre with lower level than the choised one.<br>
Priority of the LOG-level according to the Un*x-Syslog levels:<br>
0: Emergency<br>
1: Alert<br>
2: Critical<br>
3: Error<br>
4: Warning<br>
5: Notice<br>
6: Information<br>
7: Debug<br>
</dd>
</dl>

# To-do
Following things are to be implemented next:

* sending UDP to UDP-listener
* 

# Warranty | Guarantee
NO in any cases
