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
<dd>To be abele to communicate between Edomi-Server and iTunes.
In that current version only information receiving (from iTunes) is possible.
After implementation of UDP-lstener in Tune-Insturctor, sending of commands to iTunes will be possible, too
Other version of LBS19000xxx is able to communicate between Edomi-Server and iTunes via Apple-Scripts, only.
Beside of that LBS (Server), other LBS are ##needed##: "**player**", "**track**" and "**airplay**" - otional "**playlist**" and "**eq**".</dd>

<dt>Needed files ...</dt>
<dd>Apache-WebServer am Mac aktivieren und konfigurieren ... Mac-User ist das Zielverzeichnis.
Apple-Skripte - das Installationspaket ist unter folgendem Link zu finden: http://ipfelgruen.de/forum/software/skripte/eigene-tools-und-gadgets/2283-itunes-und-airplay-über-webserver-steuern
Ursprünglich wurden die Skripte für den GIRA-HS4 und EibPC entwickelt/angepasst (Senden/Empfangen) - diser LBS 19000001 kann die Skripte zur Steuerung von iTunes verwenden,
die Implementierung wird im LBS "Player" umgesetzt, als Rückmeldung werden die JSON-Dateien benötigt.
Hier auch ein Danke an Michael für die großartige Unterstützung und Perfektionierung der Aplle-Skripte sowie der einfachen Installationsroutine.
Für die Generierung benötigte JSON-Dateien bitte entweder eine individuelle Lösung entwickeln ODER den Tune?Instructor installieren (www.tune-instructor.de).
An dieser Stelle noch mein Dank an Tibor, den Entwickler von Tune?Instructor der das EXPORT (JSON-Dateien) implementiert hat.
Beschreibung für die Konfiguration von Tune?Instructor liegt in der 19000001_lbs.ZIP-Datei im Downloadbereich.</dd>

<dt>Inputs ...</dt>
<dd>
E1: TRIGGER: Systemzeit (KO 5) = 1 Sekunde, andere Trigger je nach Bedarf.
E2: DEBUG: Standard = 4.
E3: IP-ADRESSE: des Zielsystems auf dem der WebServer installiert ist und die benötigte Dateien liegen (am Mac).
E4: PATH: für die Dateiablage am NFS-SHARE in EDOMI anstatt am WebServer.
E5: USER-Name am Mac (benötigt für den WebServer).
E10: MODUS: http OR file.
E11: NAME der JSON-Datei für den String "Player".
E12: NAME der JSON-Datei für den String "Airplay".
E13: NAME der JSON-Datei für den String "Playlists".
E14: NAME der JSON-Datei für den String "eq".
E15: NAME der JSON-Datei für den String "Track".</dd>

<dt>Outpunts ...</dt>
<dd>
A1: LINK: bei Modus FILE ein SHARE und bei Modus HTTP ein URL.
A10: MODUS: darf nicht leer sein - entweder "file" oder "http".
A11: JSON-String für den LBS "Player".
A12: JSON-String für den LBS "Airplays".
A13: JSON-String für den LBS "Playlists".
A14: JSON-String für den LBS "eq".
A15: JSON-String für den LBS "Track".
</dd>
</dl>

**http/file:**
E10: wenn "file" dann Zugriff auf JSON + JPG über NFS-Freigabe in EDOMI (muss vorher erstellt werden).
E10: wenn "http" dann Zugriff auf JSON + JPG über Web-Server am Mac (Apache).

**Logik:**
blablablablabla ... siehe Bild im Zip-File.

**Log-Level**
(Beschreibung bei @wintermute 'geklaut'):
Wird ein Log-Level gesetzt, so werden nur Meldungen mit einer Priorität kleiner des gegebenen Log-Levels ins Logfile eingetragen.
Die Prioritäten für Log-Einträge entsprechen den Un*x-Syslog Leveln und lauten:
0: Emergency
1: Alert
2: Critical
3: Error
4: Warning
5: Notice
6: Informational
7: Debug

# To-do
Following things are to be implemented next:

* sending UDP to UDP-listener
* 

# Warranty | Guarantee
NO in any cases
