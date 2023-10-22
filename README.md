<p align="center">
    <a href="https://github.com/Terpz710/SimpleJoinUI/blob/main/icon.PNG"><img src="https://github.com/Terpz710/SimpleJoinUI/blob/main/icon.PNG"></img></a><br>
    <b>SimpleJoinUI plugin for Pocketmine-MP</b>

# Description
When a user joins the server for the first time it will send a form UI and title to the player. If the user already played or joined it will not send the form and title. All customizable through the config. If you have any issues please contact me via discord. Thank you!

Discord: ace873056  

# Virions/dependency

- FormUI - Jojoe77777
- Link to his virion: https://github.com/jojoe77777/FormAPI

# Config

```
# Made by Terpz710

# Messages Section
messages:
  # Title
  # The title displayed at the top of the form.
  title: "Welcome to the Server!"
  
  # Content
  # Content lines displayed in the form (each line is separated by a hyphen "-").
  # Add as many lines you need.
  content:
    - "Welcome {player}!"
    - "Follow the rules below!"
    - "Made by Terpz710"
  
  # Buttons
  # Button labels available in the form.
  buttons:
    - "ok"
    
  # Title on Click
  # Message displayed when the player clicks "ok"
  title_on_click: "Welcome!"
  
  # Subtitle Text
  # Subtitle text displayed when the player clicks "ok"
  subtitle_text: "Enjoy your stay {player}"

  # Must-Click-OK Message
  # Message displayed when the player tries to close the form without clicking "ok"
  must_click_ok_message: "You must click §eok§f to close the form."
```
