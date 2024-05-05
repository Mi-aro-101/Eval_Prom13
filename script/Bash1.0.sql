CREATE TABLE carburant(
   id_carburant SMALLINT,
   designation VARCHAR(50) NOT NULL,
   PRIMARY KEY(id_carburant),
   UNIQUE(designation)
);

CREATE TABLE prix_carburant(
   id_prix_carburant SMALLINT,
   prix CURRENCY NOT NULL,
   date_prix DATE NOT NULL,
   id_carburant SMALLINT NOT NULL,
   PRIMARY KEY(id_prix_carburant),
   FOREIGN KEY(id_carburant) REFERENCES carburant(id_carburant)
);

CREATE TABLE Marque_voiture(
   id_marque INT,
   designation VARCHAR(50) NOT NULL,
   PRIMARY KEY(id_marque),
   UNIQUE(designation)
);

CREATE TABLE modeleVoiture(
   id_modele INT,
   designation VARCHAR(50) NOT NULL,
   id_marque INT NOT NULL,
   PRIMARY KEY(id_modele),
   FOREIGN KEY(id_marque) REFERENCES Marque_voiture(id_marque)
);

CREATE TABLE voiture(
   id_voiture INT,
   matricule VARCHAR(9) NOT NULL,
   etat BYTE NOT NULL,
   id_carburant SMALLINT NOT NULL,
   id_modele INT NOT NULL,
   PRIMARY KEY(id_voiture),
   UNIQUE(id_carburant),
   UNIQUE(matricule),
   FOREIGN KEY(id_carburant) REFERENCES carburant(id_carburant),
   FOREIGN KEY(id_modele) REFERENCES modeleVoiture(id_modele)
);

CREATE TABLE utilisateur(
   id_utilisateur SMALLINT,
   email VARCHAR(100) NOT NULL,
   mot_de_passe VARCHAR(50) NOT NULL,
   roles VARCHAR(255) NOT NULL,
   PRIMARY KEY(id_utilisateur),
   UNIQUE(email),
   UNIQUE(mot_de_passe)
);

CREATE TABLE Entretien(
   id_entretien SMALLINT,
   designation VARCHAR(100) NOT NULL,
   PRIMARY KEY(id_entretien),
   UNIQUE(designation)
);

CREATE TABLE piece_voiture(
   id_piece_voiture SMALLINT,
   designation VARCHAR(100) NOT NULL,
   PRIMARY KEY(id_piece_voiture)
);

CREATE TABLE Dommage(
   id_dommage INT,
   date_dommage DATE NOT NULL,
   id_voiture INT NOT NULL,
   id_piece_voiture SMALLINT NOT NULL,
   PRIMARY KEY(id_dommage),
   FOREIGN KEY(id_voiture) REFERENCES voiture(id_voiture),
   FOREIGN KEY(id_piece_voiture) REFERENCES piece_voiture(id_piece_voiture)
);

CREATE TABLE chauffeur(
   id_chauffeur INT,
   nom VARCHAR(100) NOT NULL,
   id_utilisateur SMALLINT NOT NULL,
   PRIMARY KEY(id_chauffeur),
   UNIQUE(id_utilisateur),
   FOREIGN KEY(id_utilisateur) REFERENCES utilisateur(id_utilisateur)
);

CREATE TABLE trajet(
   id_trajet SMALLINT,
   date_trajet DATE NOT NULL,
   statut BYTE NOT NULL,
   id_voiture INT NOT NULL,
   id_chauffeur INT NOT NULL,
   PRIMARY KEY(id_trajet),
   FOREIGN KEY(id_voiture) REFERENCES voiture(id_voiture),
   FOREIGN KEY(id_chauffeur) REFERENCES chauffeur(id_chauffeur)
);

CREATE TABLE Kilometrage(
   id_kilometrage INT,
   valeur_debut DOUBLE NOT NULL,
   valeur_fin DOUBLE NOT NULL,
   id_trajet SMALLINT NOT NULL,
   PRIMARY KEY(id_kilometrage),
   FOREIGN KEY(id_trajet) REFERENCES trajet(id_trajet)
);

CREATE TABLE Entretien_voiture(
   id_entretien SMALLINT,
   id_entretien_voiture SMALLINT,
   date_entretien DATE NOT NULL,
   cout CURRENCY NOT NULL,
   id_dommage INT NOT NULL,
   PRIMARY KEY(id_entretien, id_entretien_voiture),
   UNIQUE(id_dommage),
   FOREIGN KEY(id_entretien) REFERENCES Entretien(id_entretien),
   FOREIGN KEY(id_dommage) REFERENCES Dommage(id_dommage)
);

CREATE TABLE Carburant_trajet(
   id_carburant_trajet INT,
   quantite SMALLINT NOT NULL,
   id_trajet SMALLINT NOT NULL,
   PRIMARY KEY(id_carburant_trajet),
   FOREIGN KEY(id_trajet) REFERENCES trajet(id_trajet)
);
