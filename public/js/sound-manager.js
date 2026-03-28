/**
 * SoundManager — Effets sonores procéduraux (Web Audio API)
 * Sons par type d'action + variation par élément pour les attaques
 */
const SoundManager = (function () {
    'use strict';

    let _ctx  = null;
    let _on   = true;
    let _vol  = 0.65;

    function ctx() {
        if (!_ctx) _ctx = new (window.AudioContext || window.webkitAudioContext)();
        if (_ctx.state === 'suspended') _ctx.resume();
        return _ctx;
    }

    function out() {
        const c = ctx(), g = c.createGain();
        g.gain.value = _vol;
        g.connect(c.destination);
        return g;
    }

    function noiseBuf(dur) {
        const c = ctx();
        const len = Math.ceil(c.sampleRate * dur);
        const buf = c.createBuffer(1, len, c.sampleRate);
        const d   = buf.getChannelData(0);
        for (let i = 0; i < len; i++) d[i] = Math.random() * 2 - 1;
        const src = c.createBufferSource();
        src.buffer = buf;
        return src;
    }

    const S = {

        // ── Poser une carte ────────────────────────────────────────────
        card_place() {
            const c = ctx(), t = c.currentTime, m = out();
            // Whoosh : bruit filtré bandpass descendant
            const ns = noiseBuf(0.5);
            const f  = c.createBiquadFilter();
            f.type = 'bandpass';
            f.frequency.setValueAtTime(2000, t);
            f.frequency.exponentialRampToValueAtTime(300, t + 0.35);
            f.Q.value = 1.5;
            const g = c.createGain();
            g.gain.setValueAtTime(0.001, t);
            g.gain.linearRampToValueAtTime(0.6, t + 0.05);
            g.gain.exponentialRampToValueAtTime(0.001, t + 0.45);
            ns.connect(f); f.connect(g); g.connect(m);
            ns.start(t); ns.stop(t + 0.5);
            // Tone magique
            const osc = c.createOscillator();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(800, t);
            osc.frequency.exponentialRampToValueAtTime(350, t + 0.25);
            const og = c.createGain();
            og.gain.setValueAtTime(0.001, t);
            og.gain.linearRampToValueAtTime(0.22, t + 0.04);
            og.gain.exponentialRampToValueAtTime(0.001, t + 0.28);
            osc.connect(og); og.connect(m);
            osc.start(t); osc.stop(t + 0.3);
        },

        // ── Attaque générique ──────────────────────────────────────────
        attack_generic() {
            const c = ctx(), t = c.currentTime, m = out();
            const osc = c.createOscillator();
            osc.frequency.setValueAtTime(160, t);
            osc.frequency.exponentialRampToValueAtTime(40, t + 0.28);
            const og = c.createGain();
            og.gain.setValueAtTime(0.95, t);
            og.gain.exponentialRampToValueAtTime(0.001, t + 0.28);
            osc.connect(og); og.connect(m); osc.start(t); osc.stop(t + 0.3);
            const ns = noiseBuf(0.18);
            const ng = c.createGain();
            ng.gain.setValueAtTime(0.5, t);
            ng.gain.exponentialRampToValueAtTime(0.001, t + 0.18);
            ns.connect(ng); ng.connect(m); ns.start(t); ns.stop(t + 0.18);
        },

        // ── Feu : impact + crépitement ─────────────────────────────────
        attack_fire() {
            const c = ctx(), t = c.currentTime, m = out();
            const osc = c.createOscillator();
            osc.frequency.setValueAtTime(160, t);
            osc.frequency.exponentialRampToValueAtTime(40, t + 0.28);
            const og = c.createGain();
            og.gain.setValueAtTime(0.95, t); og.gain.exponentialRampToValueAtTime(0.001, t + 0.28);
            osc.connect(og); og.connect(m); osc.start(t); osc.stop(t + 0.3);
            const ns = noiseBuf(0.18);
            const ng = c.createGain();
            ng.gain.setValueAtTime(0.5, t); ng.gain.exponentialRampToValueAtTime(0.001, t + 0.18);
            ns.connect(ng); ng.connect(m); ns.start(t); ns.stop(t + 0.18);
            // Crépitements
            for (let i = 0; i < 6; i++) {
                const cr = noiseBuf(0.07);
                const fh = c.createBiquadFilter();
                fh.type = 'highpass';
                fh.frequency.value = 2500 + Math.random() * 3000;
                const cg = c.createGain();
                const st = t + 0.08 + i * 0.07 + Math.random() * 0.02;
                cg.gain.setValueAtTime(0.28, st); cg.gain.exponentialRampToValueAtTime(0.001, st + 0.07);
                cr.connect(fh); fh.connect(cg); cg.connect(m);
                cr.start(st); cr.stop(st + 0.07);
            }
        },

        // ── Eau : impact + splash ──────────────────────────────────────
        attack_water() {
            const c = ctx(), t = c.currentTime, m = out();
            const osc = c.createOscillator();
            osc.frequency.setValueAtTime(100, t);
            osc.frequency.exponentialRampToValueAtTime(28, t + 0.3);
            const og = c.createGain();
            og.gain.setValueAtTime(0.7, t); og.gain.exponentialRampToValueAtTime(0.001, t + 0.3);
            osc.connect(og); og.connect(m); osc.start(t); osc.stop(t + 0.3);
            const ns = noiseBuf(0.6);
            const f  = c.createBiquadFilter();
            f.type = 'bandpass'; f.frequency.value = 420; f.Q.value = 0.8;
            const ng = c.createGain();
            ng.gain.setValueAtTime(0.001, t);
            ng.gain.linearRampToValueAtTime(0.55, t + 0.05);
            ng.gain.exponentialRampToValueAtTime(0.001, t + 0.55);
            ns.connect(f); f.connect(ng); ng.connect(m); ns.start(t); ns.stop(t + 0.6);
        },

        // ── Glace : impact + cristaux ──────────────────────────────────
        attack_ice() {
            const c = ctx(), t = c.currentTime, m = out();
            const osc = c.createOscillator();
            osc.frequency.setValueAtTime(220, t);
            osc.frequency.exponentialRampToValueAtTime(50, t + 0.12);
            const og = c.createGain();
            og.gain.setValueAtTime(0.85, t); og.gain.exponentialRampToValueAtTime(0.001, t + 0.12);
            osc.connect(og); og.connect(m); osc.start(t); osc.stop(t + 0.14);
            [1400, 1900, 2300, 2800].forEach((freq, i) => {
                const ch = c.createOscillator();
                ch.type = 'sine';
                ch.frequency.value = freq + (Math.random() - 0.5) * 60;
                const cg = c.createGain();
                const st = t + 0.05 + i * 0.05;
                cg.gain.setValueAtTime(0.18, st); cg.gain.exponentialRampToValueAtTime(0.001, st + 0.4);
                ch.connect(cg); cg.connect(m); ch.start(st); ch.stop(st + 0.45);
            });
        },

        // ── Tonnerre : crack + grondement ─────────────────────────────
        attack_thunder() {
            const c = ctx(), t = c.currentTime, m = out();
            const osc = c.createOscillator();
            osc.frequency.setValueAtTime(450, t);
            osc.frequency.exponentialRampToValueAtTime(22, t + 0.1);
            const og = c.createGain();
            og.gain.setValueAtTime(1.0, t); og.gain.exponentialRampToValueAtTime(0.001, t + 0.1);
            osc.connect(og); og.connect(m); osc.start(t); osc.stop(t + 0.12);
            const snap = c.createOscillator();
            snap.frequency.value = 1400;
            const sg = c.createGain();
            sg.gain.setValueAtTime(0.55, t); sg.gain.exponentialRampToValueAtTime(0.001, t + 0.04);
            snap.connect(sg); sg.connect(m); snap.start(t); snap.stop(t + 0.05);
            const ns = noiseBuf(0.5);
            const f  = c.createBiquadFilter();
            f.type = 'lowpass'; f.frequency.value = 180;
            const ng = c.createGain();
            ng.gain.setValueAtTime(0.65, t); ng.gain.exponentialRampToValueAtTime(0.001, t + 0.4);
            ns.connect(f); f.connect(ng); ng.connect(m); ns.start(t); ns.stop(t + 0.5);
        },

        // ── Ténèbres : impact sombre et profond ────────────────────────
        attack_darkness() {
            const c = ctx(), t = c.currentTime, m = out();
            const osc = c.createOscillator();
            osc.frequency.setValueAtTime(75, t);
            osc.frequency.exponentialRampToValueAtTime(18, t + 0.5);
            const og = c.createGain();
            og.gain.setValueAtTime(0.95, t); og.gain.exponentialRampToValueAtTime(0.001, t + 0.5);
            osc.connect(og); og.connect(m); osc.start(t); osc.stop(t + 0.55);
            const ns = noiseBuf(0.6);
            const f  = c.createBiquadFilter();
            f.type = 'lowpass'; f.frequency.value = 140;
            const ng = c.createGain();
            ng.gain.setValueAtTime(0.55, t); ng.gain.exponentialRampToValueAtTime(0.001, t + 0.55);
            ns.connect(f); f.connect(ng); ng.connect(m); ns.start(t); ns.stop(t + 0.6);
        },

        // ── Lumière : impact + éclat ───────────────────────────────────
        attack_light() {
            const c = ctx(), t = c.currentTime, m = out();
            const osc = c.createOscillator();
            osc.frequency.setValueAtTime(160, t);
            osc.frequency.exponentialRampToValueAtTime(40, t + 0.28);
            const og = c.createGain();
            og.gain.setValueAtTime(0.9, t); og.gain.exponentialRampToValueAtTime(0.001, t + 0.28);
            osc.connect(og); og.connect(m); osc.start(t); osc.stop(t + 0.3);
            const ns = noiseBuf(0.15);
            const ng = c.createGain();
            ng.gain.setValueAtTime(0.4, t); ng.gain.exponentialRampToValueAtTime(0.001, t + 0.15);
            ns.connect(ng); ng.connect(m); ns.start(t); ns.stop(t + 0.15);
            [1800, 2400, 3000, 3800].forEach((freq, i) => {
                const ch = c.createOscillator();
                ch.type = 'sine'; ch.frequency.value = freq;
                const cg = c.createGain();
                const st = t + i * 0.03;
                cg.gain.setValueAtTime(0.15, st); cg.gain.exponentialRampToValueAtTime(0.001, st + 0.28);
                ch.connect(cg); cg.connect(m); ch.start(st); ch.stop(st + 0.3);
            });
        },

        // ── Carte détruite : explosion ─────────────────────────────────
        destroy() {
            const c = ctx(), t = c.currentTime, m = out();
            const osc = c.createOscillator();
            osc.frequency.setValueAtTime(85, t);
            osc.frequency.exponentialRampToValueAtTime(18, t + 0.6);
            const og = c.createGain();
            og.gain.setValueAtTime(1.0, t); og.gain.exponentialRampToValueAtTime(0.001, t + 0.6);
            osc.connect(og); og.connect(m); osc.start(t); osc.stop(t + 0.65);
            const ns = noiseBuf(0.3);
            const ng = c.createGain();
            ng.gain.setValueAtTime(0.85, t); ng.gain.exponentialRampToValueAtTime(0.001, t + 0.3);
            ns.connect(ng); ng.connect(m); ns.start(t); ns.stop(t + 0.3);
            for (let i = 0; i < 5; i++) {
                const cr = noiseBuf(0.1);
                const fh = c.createBiquadFilter();
                fh.type = 'highpass'; fh.frequency.value = 1500 + Math.random() * 2500;
                const cg = c.createGain();
                const st = t + 0.15 + i * 0.1 + Math.random() * 0.05;
                cg.gain.setValueAtTime(0.3, st); cg.gain.exponentialRampToValueAtTime(0.001, st + 0.1);
                cr.connect(fh); fh.connect(cg); cg.connect(m);
                cr.start(st); cr.stop(st + 0.1);
            }
        },

        // ── Fin de tour : gong ─────────────────────────────────────────
        end_turn() {
            const c = ctx(), t = c.currentTime, m = out();
            const ns = noiseBuf(0.02);
            const ng = c.createGain();
            ng.gain.setValueAtTime(0.35, t); ng.gain.exponentialRampToValueAtTime(0.001, t + 0.02);
            ns.connect(ng); ng.connect(m); ns.start(t); ns.stop(t + 0.02);
            const osc = c.createOscillator();
            osc.type = 'sine'; osc.frequency.value = 88;
            const og = c.createGain();
            og.gain.setValueAtTime(0.001, t);
            og.gain.linearRampToValueAtTime(0.65, t + 0.01);
            og.gain.exponentialRampToValueAtTime(0.001, t + 1.8);
            osc.connect(og); og.connect(m); osc.start(t); osc.stop(t + 2.0);
            const osc2 = c.createOscillator();
            osc2.type = 'sine'; osc2.frequency.value = 182;
            const og2 = c.createGain();
            og2.gain.setValueAtTime(0.001, t);
            og2.gain.linearRampToValueAtTime(0.28, t + 0.01);
            og2.gain.exponentialRampToValueAtTime(0.001, t + 0.9);
            osc2.connect(og2); og2.connect(m); osc2.start(t); osc2.stop(t + 1.0);
        },

        // ── Victoire : fanfare ascendante ──────────────────────────────
        victory() {
            const c = ctx(), t = c.currentTime, m = out();
            [523.25, 659.25, 783.99, 1046.50].forEach((freq, i) => {
                const st = t + i * 0.15;
                const osc = c.createOscillator();
                osc.type = 'triangle'; osc.frequency.value = freq;
                const og = c.createGain();
                og.gain.setValueAtTime(0.001, st);
                og.gain.linearRampToValueAtTime(0.5, st + 0.02);
                og.gain.exponentialRampToValueAtTime(0.001, st + 0.55);
                osc.connect(og); og.connect(m); osc.start(st); osc.stop(st + 0.6);
                const h = c.createOscillator();
                h.type = 'sine'; h.frequency.value = freq * 2;
                const hg = c.createGain();
                hg.gain.setValueAtTime(0.001, st);
                hg.gain.linearRampToValueAtTime(0.15, st + 0.02);
                hg.gain.exponentialRampToValueAtTime(0.001, st + 0.3);
                h.connect(hg); hg.connect(m); h.start(st); h.stop(st + 0.35);
            });
            [523.25, 659.25, 783.99].forEach(freq => {
                const osc = c.createOscillator();
                osc.type = 'triangle'; osc.frequency.value = freq;
                const og = c.createGain();
                const st = t + 0.65;
                og.gain.setValueAtTime(0.001, st);
                og.gain.linearRampToValueAtTime(0.32, st + 0.05);
                og.gain.exponentialRampToValueAtTime(0.001, st + 1.1);
                osc.connect(og); og.connect(m); osc.start(st); osc.stop(st + 1.2);
            });
        },

        // ── Défaite : descente mineure solennelle ──────────────────────
        defeat() {
            const c = ctx(), t = c.currentTime, m = out();
            [523.25, 466.16, 415.30, 392.00].forEach((freq, i) => {
                const st = t + i * 0.23;
                const osc = c.createOscillator();
                osc.type = 'sine'; osc.frequency.value = freq;
                const og = c.createGain();
                og.gain.setValueAtTime(0.001, st);
                og.gain.linearRampToValueAtTime(0.4, st + 0.05);
                og.gain.exponentialRampToValueAtTime(0.001, st + 0.55);
                osc.connect(og); og.connect(m); osc.start(st); osc.stop(st + 0.6);
            });
            const osc = c.createOscillator();
            osc.type = 'sine'; osc.frequency.value = 196.00;
            const og = c.createGain();
            og.gain.setValueAtTime(0.001, t + 0.85);
            og.gain.linearRampToValueAtTime(0.35, t + 0.9);
            og.gain.exponentialRampToValueAtTime(0.001, t + 2.2);
            osc.connect(og); og.connect(m); osc.start(t + 0.85); osc.stop(t + 2.3);
        }
    };

    return {
        toggle()         { _on = !_on; return _on; },
        setVolume(v)     { _vol = Math.max(0, Math.min(1, v)); },
        isEnabled()      { return _on; },

        play(action, element) {
            if (!_on) return;
            if (!(window.AudioContext || window.webkitAudioContext)) return;
            try {
                if (action === 'attack') {
                    const key = 'attack_' + (element || 'generic');
                    (S[key] || S.attack_generic).call(S);
                    return;
                }
                if (S[action]) S[action].call(S);
            } catch (e) {
                console.warn('[SoundManager]', e);
            }
        }
    };
})();
