# Contributing

Thanks for helping. Small, focused changes are easiest to review.

1. Fork the repository and create a branch.
2. Make your change. Edit `assets/css/src/*.css`, never `assets/css/main.css` directly.
3. Run `bash bin/build-assets.sh`, then `bash bin/check.sh`. It must print `OK`.
4. Test in English and Arabic (right-to-left), at 390, 820 and 1440 px wide, and with the keyboard.
5. Open a pull request and describe what you changed and how you tested it.

New visible text must go through the translation functions (text domain `rashid`).
