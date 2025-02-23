@echo off
:loop
set /p input="Enter Commit Text: "
if "%input%"=="" (
    echo You must enter a commit message!
    goto loop
)
set date=%DATE%
set commit=git commit -m
set gitcommit=%commit% "%input% - %date% %TIME%"
git add .
%gitcommit%
git pull
git push --all
echo Process Complete Press Enter...
pause