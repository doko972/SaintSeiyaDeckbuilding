## FonctionnalitéComplexitéBonus quotidien (daily login)⭐
## Historique des combats⭐
## Classement des joueurs⭐⭐
## Combat PvP en temps réel⭐⭐⭐
## Système de quêtes/missions⭐⭐
## Événements temporaires⭐⭐
## Échange de cartes entre joueurs⭐⭐⭐
## Amélioration/fusion de cartes⭐⭐
## Animations de combat (Canvas/WebGL)⭐⭐⭐
## Application mobile (PWA)⭐⭐

# Installer les dépendances :
```
composer install
npm install
```

cp .env.example .env
php artisan key:generate
```

## Ensuite, configure la base de données dans `.env` :
```
DB_DATABASE=saint_seiya
DB_USERNAME=root
DB_PASSWORD=


##  Lancer les seeders
php artisan db:seed

## Ou pour tout faire d'un coup (migrate + seed) :
php artisan migrate:fresh --seed

## Lien symbolique pour le storage (images)
php artisan storage:link

## Compiler les assets
npm run build

##  Lancer le serveur
php artisan serve

# Pour l'exemple : 
## Supprimer les anciens fichiers seeder
rm database/seeders/CardSeeder.php
rm database/seeders/AttackSeeder.php


## Réinitialiser la base et relancer toutes les migrations


# 🎴 TABLEAU DE RÉFÉRENCE RAPIDE - ARCHÉTYPES

## 📊 LES 6 ARCHÉTYPES

| 🛡️ TANK | 💪 BRUISER | ⚔️ ATTAQUANT | ⚖️ ÉQUILIBRÉ | ⚡ AGILE | 🧠 TECHNIQUE |
|----------|------------|--------------|--------------|----------|--------------|
| Mur défensif | Tank offensif | Glass cannon | Polyvalent | Multi-attaques | Défense focus |
| PV: 40% | PV: 35% | PV: 25% | PV: 30% | PV: 25% | PV: 30% |
| END: 25% | END: 25% | END: 25% | END: 25% | END: 35% | END: 20% |
| DEF: 25% | DEF: 20% | DEF: 15% | DEF: 20% | DEF: 15% | DEF: 30% |
| PWR: 10% | PWR: 20% | PWR: 35% | PWR: 25% | PWR: 25% | PWR: 20% |

---

## 🎯 STATS PAR COÛT ET ARCHÉTYPE

### COÛT 3 (Bronze) - Budget 60
| Archétype | PV | END | DEF | PWR | COS |
|-----------|-----|-----|-----|-----|-----|
| 🛡️ Tank | **110** | 40 | **40** | 15 | 3 |
| 💪 Bruiser | 95 | 50 | 35 | 25 | 3 |
| ⚔️ Attaquant | 70 | 50 | 25 | **35** | 3 |
| ⚖️ Équilibré | 85 | 50 | 30 | 28 | 3 |
| ⚡ Agile | 70 | **60** | 20 | 28 | 3 |
| 🧠 Technique | 85 | 40 | **45** | 22 | 3 |

### COÛT 4 (Bronze Elite) - Budget 80
| Archétype | PV | END | DEF | PWR | COS |
|-----------|-----|-----|-----|-----|-----|
| 🛡️ Tank | **140** | 50 | **50** | 20 | 4 |
| 💪 Bruiser | 120 | 65 | 45 | 35 | 4 |
| ⚔️ Attaquant | 90 | 65 | 30 | **48** | 4 |
| ⚖️ Équilibré | 110 | 65 | 40 | 38 | 4 |
| ⚡ Agile | 90 | **75** | 25 | 38 | 4 |
| 🧠 Technique | 110 | 50 | **55** | 30 | 4 |

### COÛT 5 (Argent) - Budget 100
| Archétype | PV | END | DEF | PWR | COS |
|-----------|-----|-----|-----|-----|-----|
| 🛡️ Tank | **170** | 60 | **60** | 25 | 5 |
| 💪 Bruiser | 150 | 80 | 55 | 45 | 5 |
| ⚔️ Attaquant | 110 | 80 | 35 | **60** | 5 |
| ⚖️ Équilibré | 135 | 80 | 50 | 48 | 5 |
| ⚡ Agile | 110 | **90** | 30 | 48 | 5 |
| 🧠 Technique | 135 | 60 | **65** | 38 | 5 |

### COÛT 6 (Argent Elite) - Budget 120
| Archétype | PV | END | DEF | PWR | COS |
|-----------|-----|-----|-----|-----|-----|
| 🛡️ Tank | **200** | 70 | **70** | 30 | 6 |
| 💪 Bruiser | 175 | 90 | 65 | 55 | 6 |
| ⚔️ Attaquant | 130 | 90 | 40 | **70** | 6 |
| ⚖️ Équilibré | 160 | 90 | 60 | 58 | 6 |
| ⚡ Agile | 130 | **105** | 35 | 58 | 6 |
| 🧠 Technique | 160 | 70 | **75** | 45 | 6 |

### COÛT 7 (Or) - Budget 140
| Archétype | PV | END | DEF | PWR | COS |
|-----------|-----|-----|-----|-----|-----|
| 🛡️ Tank | **230** | 80 | **80** | 35 | 7 |
| 💪 Bruiser | 200 | 100 | 75 | 65 | 7 |
| ⚔️ Attaquant | 150 | 100 | 45 | **85** | 7 |
| ⚖️ Équilibré | 180 | 100 | 70 | 68 | 7 |
| ⚡ Agile | 150 | **120** | 40 | 68 | 7 |
| 🧠 Technique | 180 | 80 | **90** | 52 | 7 |

### COÛT 8 (Or Fort) - Budget 160
| Archétype | PV | END | DEF | PWR | COS |
|-----------|-----|-----|-----|-----|-----|
| 🛡️ Tank | **260** | 90 | **90** | 40 | 8 |
| 💪 Bruiser | 225 | 110 | 85 | 75 | 8 |
| ⚔️ Attaquant | 170 | 110 | 50 | **95** | 8 |
| ⚖️ Équilibré | 205 | 110 | 80 | 78 | 8 |
| ⚡ Agile | 170 | **135** | 45 | 78 | 8 |
| 🧠 Technique | 205 | 90 | **100** | 60 | 8 |

### COÛT 9 (Or Elite) - Budget 180
| Archétype | PV | END | DEF | PWR | COS |
|-----------|-----|-----|-----|-----|-----|
| 🛡️ Tank | **290** | 100 | **100** | 45 | 9 |
| 💪 Bruiser | 250 | 120 | 95 | 85 | 9 |
| ⚔️ Attaquant | 190 | 120 | 55 | **110** | 9 |
| ⚖️ Équilibré | 230 | 120 | 90 | 88 | 9 |
| ⚡ Agile | 190 | **150** | 50 | 88 | 9 |
| 🧠 Technique | 230 | 100 | **110** | 68 | 9 |

### COÛT 10 (Dieu) - Budget 200
| Archétype | PV | END | DEF | PWR | COS |
|-----------|-----|-----|-----|-----|-----|
| 🛡️ Tank | **320** | 110 | **110** | 50 | 10 |
| 💪 Bruiser | 280 | 130 | 105 | 95 | 10 |
| ⚔️ Attaquant | 210 | 130 | 60 | **120** | 10 |
| ⚖️ Équilibré | 255 | 130 | 100 | 98 | 10 |
| ⚡ Agile | 210 | **165** | 55 | 98 | 10 |
| 🧠 Technique | 255 | 110 | **125** | 75 | 10 |

---

## 🎯 GUIDE RAPIDE D'UTILISATION

### 1️⃣ Choisis le COÛT (3-10)
- **3-4** : Bronze Saints
- **5-6** : Silver Saints
- **7-9** : Gold Saints
- **10** : Dieux

### 2️⃣ Choisis l'ARCHÉTYPE
- **🛡️ Tank** : Encaisse tout
- **💪 Bruiser** : Équilibre défense/attaque
- **⚔️ Attaquant** : Frappe fort mais fragile
- **⚖️ Équilibré** : Polyvalent
- **⚡ Agile** : Attaque plusieurs fois
- **🧠 Technique** : Défense ultime

### 3️⃣ Copie les STATS du tableau
Utilise exactement les valeurs ou ajuste légèrement (±10%)

### 4️⃣ Crée les ATTAQUES
**Formule des dégâts d'attaque :**
```
Dégâts = 50 + (PWR × 0.5 à 1.5)

Attaque faible : PWR × 0.5
Attaque moyenne : PWR × 1
Attaque forte : PWR × 1.5
```

**Coûts suggérés :**
```
Attaque 1 (basique) : 0-2 COS, 10-20 END
Attaque 2 (moyenne) : 2-5 COS, 20-40 END
Attaque 3 (ultime) : 5-8 COS, 40-70 END
```

---

## ✅ EXEMPLES CONCRETS

### 🛡️ Aldébaran (Tank Coût 7)
```
❤️ 230 PV  ⚡ 80 END  🛡️ 80 DEF  💪 35 PWR  🌟 7 COS

Attaques :
- Great Horn : 2 COS, 25 END → 50 dmg
- Titan's Breakthrough : 5 COS, 50 END → 85 dmg
```

### ⚔️ Ikki (Attaquant Coût 6)
```
❤️ 130 PV  ⚡ 90 END  🛡️ 40 DEF  💪 70 PWR  🌟 6 COS

Attaques :
- Phoenix Wing : 3 COS, 30 END → 85 dmg
- Hō Yoku Ten Shō : 6 COS, 60 END → 140 dmg
```

### ⚖️ Seiya (Équilibré Coût 4)
```
❤️ 110 PV  ⚡ 65 END  🛡️ 40 DEF  💪 38 PWR  🌟 4 COS

Attaques :
- Pegasus Meteor Fist : 2 COS, 20 END → 60 dmg
- Pegasus Ryu Sei Ken : 4 COS, 40 END → 95 dmg
```

---

## 📋 CHECKLIST CRÉATION RAPIDE

```
□ Coût déterminé (3-10)
□ Archétype choisi
□ Stats copiées du tableau
□ 3 attaques créées (faible/moyenne/forte)
□ Coûts cosmos attaques cohérents
□ Test : Total ≈ Coût × 20
```

---

## 💡 TIPS RAPIDES

✅ **Respecte les pourcentages** des archétypes
✅ **Utilise le tableau** tel quel pour 90% des cartes
✅ **Ajuste légèrement** (±10%) pour la variété
✅ **Vérifie toujours** : PV + END + DEF + (PWR×2) ≈ Coût×20
✅ **Les attaques** doivent coûter moins que le cosmos max (10)

❌ **N'invente pas** de nouvelles formules
❌ **Ne mixe pas** trop les archétypes
❌ **N'oublie pas** que PWR compte double dans l'équilibrage

---

