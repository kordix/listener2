let app = new Vue({
    el: '#app',
    data: {
        error: '',
        run: false,
        piosenka: '',
        mytext: '',
        dane: [],
        fragmenty: {},
        fragmentindex: 0,
        napisy: false,
        alltext: false,
        przesuniecie: 0,
        przedluzenie: 0,
        songsheaders:[],
        fragmenty2:[

        ],
        messages:[]

    },
    mounted() {
        let self = this;
    
        fetch('/api/getdata.php').then((res) => res.json()).then((res) => {console.log(res); self.dane = res });
        fetch('/api/getsongsheaders.php').then((res) => res.json()).then((res) => { console.log(res); self.songsheaders = res; });

    },
    methods: {
        pauser(){
            let self = this;
            let audi = document.getElementById('audioelem');
            audi.pause();
            self.run = false;

        },
        setSong() {
            console.log('setSong');
            let self = this;
            this.error = '';
            this.fragmentindex = -1;
            console.log('SET SONG');
            this.fragmenty = this.dane.filter((el)=>el.song == self.piosenka )
        },
        longer(time){
            this.fragmenty[this.fragmentindex].start = parseFloat(parseFloat(this.fragmenty[this.fragmentindex].start) + parseFloat(time));
            // this.fragmenty[this.fragmentindex + 1].start += time;
            fetch('/api/updatedata.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(this.fragmenty[this.fragmentindex]) })

            this.messages.push('Przesunięto na ' + this.fragmenty[this.fragmentindex].start);
        },
        longerAll(time) {
            this.fragmenty.forEach(element => {
                element.start = parseFloat(parseFloat(element.start) + parseFloat(time));
            });

            fetch('/api/updaterall.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(this.fragmenty) })
        },


        play() {
            this.run = true;
            document.getElementById('napisy').innerHTML = '';


            let self = this;
            let audi = document.getElementById('audioelem');

            console.log(self.fragmentindex);
            console.log(self.fragmenty[self.fragmentindex]);

            audi.currentTime = parseFloat(self.fragmenty[self.fragmentindex].start) - 0.5;
            console.log(audi.currentTime);
            audi.play();

            let duration = self.fragmenty[self.fragmentindex + 1].start - self.fragmenty[self.fragmentindex].start;

            setTimeout(function () {
                audi.pause();
                self.run = false;
                
            }, duration * 1000 + 500);
            

        },
        next() {
            console.log('NEXT');
            if (this.fragmentindex >= this.fragmenty.length - 1) {
                this.error = 'KONIEC';

                console.log('koniec');
                return;
            }

            this.fragmentindex = this.fragmentindex + 1;
            this.play();
        },

        podejrzyjnapisym() {
            document.getElementById('napisy').innerHTML = this.fragmenty[this.fragmentindex].tekst;
        },
        showAllText() {
            let self = this;
            self.alltext = !self.alltext;

        }
    }

})
