// This class is representing the audio player.
// This class container all the methods control
// the lecture audio.

class Player {
    constructor(settings) {
        this.sound;
        this.actualBlock = 0;
        this.blockOrder = [];
        this.sprites = settings['sprites'];
        this.totalBlocks = Object.keys(this.sprites).length;
        this.playMode = settings['playMode'];
        this.pause = parseFloat(settings['pause']) * 1000;
        this.pauseRepeat = parseFloat(settings['pauseRepeat']) * 1000;
        this.settings = settings;

        this.playTimeOut = null;
        this.paused = false;
        this.pausedBlock = 0;
        this.pausedBlockRepeat = 0;

        this.orginalTextBlocks = settings['txtBlocks'];
        this.paralelTextBlocks = settings['paralelBlocks'];

        this.blockRepeatCount = parseInt(settings['repeat']);
        this.blockPlayedCount = 0;

        this.waitForBtn = false;
        this.blockOrder = this.setUpBlockOrder();

        if (settings["local"] == false) {
            this.sound = new Howl({
                src: [settings['audio']],
                sprite: this.sprites
            });
        } else {
            this.sound = settings["audio"]
        }

        if (this.playMode == "3" || this.playMode == "5" || this.settings['pause'] == "99") {
            this.waitForBtn = true;
        }

        if (this.settings['pause'] == "99"){
          this.pause = 0;
        }
        console.log(this.settings);
    }

    // This method starts playing the lecture.
    play() {
        if (this.sound.playing()) {
            return;
        };
        // play
        if (this.actualBlock < this.totalBlocks && this.actualBlock > -1) {
            // original block
            if (this.settings['script'] && this.playMode != "5") {
                document.getElementById('original-text').innerHTML = this.orginalTextBlocks[this.blockOrder[this.actualBlock]];
            } else {
                document.getElementById('original-text').innerHTML = "";
            }
            // trans blocks
            if (this.paralelTextBlocks != null && this.settings['trans']) {
                document.getElementById('paralel-text').innerHTML = this.paralelTextBlocks[this.blockOrder[this.actualBlock]];
            } else {
                document.getElementById('paralel-text').innerHTML = "";
            }

            var tmpPlayer = this;

            var playDelay = this.playMode == "4" ? 500 : 0;

            if (!tmpPlayer.waitForBtn && !this.paused) {
                setTimeout(function() {
                    tmpPlayer.sound.play('block_' + tmpPlayer.blockOrder[tmpPlayer.actualBlock]);
                    $(document).trigger('progressBarChangeEvent');
                }, playDelay)
            }

            var timeOutTime = 500;
            if (!this.waitForBtn){
              if (this.blockPlayedCount < this.blockRepeatCount) {
                  timeOutTime = this.pauseRepeat;
              } else {
                  timeOutTime = this.pause;
              }
            }
            else {
              timeOutTime = 10;
            }


            tmpPlayer.sound.once('end', function() {
                if (tmpPlayer.paused)
                    return;

                if (tmpPlayer.blockPlayedCount < tmpPlayer.blockRepeatCount) {
                    tmpPlayer.blockPlayedCount++;
                } else {
                    tmpPlayer.actualBlock++;
                    tmpPlayer.blockPlayedCount = 0;
                }

                setTimeout(function() {
                    tmpPlayer.play();
                }, timeOutTime);
            });
        } else {
            this.actualBlock = 0;
            this.blockPlayedCount = 0;

            if (this.playMode == "5" || this.playMode == "3" || this.playMode == "1") {
                this.play();
            } else {
                $(document).trigger('lectureEndedEvent');
            }
        }
    }


    // this method calculetes the sequence of blocks
    setUpBlockOrder() {
        var order = [];

        for (var i = 0; i < this.totalBlocks; i++) {
            order.push(i)
        }

        if (this.settings['direction'] == true)
            return this.shuffle(order);
        else
            return order;
    }

    // this is a helper function for a shuffleing an array
    // @param a, a array to shuffle
    shuffle(a) {
        var j, x, i;
        for (i = a.length - 1; i > 0; i--) {
            j = Math.floor(Math.random() * (i + 1));
            x = a[i];
            a[i] = a[j];
            a[j] = x;
        }
        return a;
    }

    // Pauses the lecture
    pauseSound() {
        this.clearAllTimeOuts();
        this.sound.pause();
        this.sound.off();

        this.paused = true;
        this.pausedBlock = this.actualBlock;
        this.pausedBlockRepeat = this.blockPlayedCount;
    }


    // skip to the next block in order
    next() {
        this.clearAllTimeOuts();
        this.sound.stop();
        this.sound.off();

        if (this.actualBlock < this.totalBlocks - 1) {
            this.actualBlock++;
            this.blockPlayedCount = 0;
            this.play();

        } else {
            this.actualBlock = 0;
            this.blockPlayedCount = 0;
        }
    }

    // get back to the previous block in order
    back() {
        this.clearAllTimeOuts();
        this.sound.stop();
        this.sound.off();

        if (this.actualBlock > 0) {
            this.actualBlock--;
            this.blockPlayedCount = 0;
            this.play();
        } else {
            this.actualBlock = 0;
            this.blockPlayedCount = 0;
        }
    }

    // help function the check the answer in mode Pronounciation check and Speaking check
    playSound() {
        if (this.sound.playing())
            return;

        if (this.playMode == "5") {
            document.getElementById('original-text').innerHTML = this.orginalTextBlocks[this.blockOrder[this.actualBlock]];
        }
        this.sound.play('block_' + this.blockOrder[this.actualBlock]);
        $(document).trigger('progressBarChangeEvent');
    }

    // helper function to clear all tiemouts
    clearAllTimeOuts() {
        var id = window.setTimeout(function() {}, 0);

        while (id--) {
            window.clearTimeout(id); // will do nothing if no timeout with id is present
        }

        window.clearTimeout(this.playTimeOut);
        this.playTimeOut = null;
    }
}
