#!/usr/bin/env bash
# 
# Global hotkey to unmute zoom, regardless of active window.
# 
# @credit http://pzel.name/til/2019/11/25/Muting-and-unmuting-Zoom-from-anywhere-on-the-linux-desktop.html

#CURRENT=$(xdotool getwindowfocus)

ZOOM=$(xdotool search --limit 1 --name "^Zoom Meeting")
xdotool windowactivate --sync ${ZOOM}
xdotool key --clearmodifiers "alt+a"

# optionally switch back, but usually if I'm talking, I'd want to zoom to be at the forfront
# xdotool windowactivate --sync ${CURRENT}