from math import exp, log
import numpy as np
import matplotlib.pyplot as plt

'''
    Calcule le taux effectif
'''
def tauxEffectif(taux, duree):
    taux = taux/100
    return (1+taux/duree)**duree-1

'''
    Calcule l'argent total après une certaine duree selon le taux effectif et un placement initial
'''
def placement(P0, duree, taux):
    taux = taux /100
    return P0*(1+taux)**duree
    
def placementSimple(P0, duree, taux):
    taux = taux /100
    return P0+(P0*taux*duree)   
    
'''
    Calcule l'argent total après une certaine duree selon le taux effectif et un placement initial
'''
def placementParMois(P0, duree, mois, taux):
    taux = taux / 100
    reff = (1+(taux/mois))**mois -1
    return P0*exp(reff*duree)
    
'''
    Calcule le montant de l'épargne après une certaine durée selon un taux et le placement effectué chaque année (boucle)
'''
def epargne(delta, duree, taux):
    taux = taux /100
    somme = 0
    for i in range(duree):
        somme = somme +(1+taux)**i
    return (delta * (1+taux)**duree + delta * somme) - delta # n ajoute pas d'argent la dernière année car on va retirer l'argent
    
'''
    Calcule le montant de l'épargne après une certaine durée selon un taux et le placement effectué chaque année 
'''
def epargne2(delta, duree, taux):
    taux = taux / 100
    return (delta / taux) * (( 1 + taux)**(duree +1) - 1) - delta #on ajoute pas d'argent la dernière année car on va retirer l'argent
    
'''
    affiche le montant pour chaque année d'une épargne
'''
def epargneTableau(delta, duree, taux):
    somme = []
    for i in range(1, duree+1):
        resultat= epargne2(delta, i, taux)
        somme.append(resultat)
        print("annee " +str(i) +" = " + str(resultat))
    return somme
    
'''
    Calcule l'annee pour laqu'elle le rendement annuel aura double
'''
def anneeDoubleRendAnnuel(delta, duree, taux):
    x=duree
    while(rendAnnuel(delta,x,taux) < 2*rendAnnuel(delta, duree, taux)):
        x=x+1
    return x 
    
'''
    Calcule le rendement annuel d'interêt composé
'''
def rendAnnuel(delta, duree, taux):
    pla = placement(delta, duree, taux)
    return (pla-delta)/duree
    
'''
    Calcule le rendement annuel d'interêt simple
'''    
def rendAnnuelSimple(delta, duree, taux):
    pla = placementSimple(delta, duree, taux)
    return (pla-delta)/duree
    
    
'''
    Résoud l'exo 4B
'''
def ex4B(deltaA, dureeA, taux, duree):
    placementA = placement(deltaA, dureeA, taux)
    x=1000
    while(placement(x,duree,5) < placementA):
        x=x+1
    return x

'''
    Calcul du montant à payer (delta) par mois d'un emprunt 'montantInit', de durée 'duree' (en année) avec un taux mensuel = 'taux' (compris entre 0 et 1).
'''
def remboursementMens(montantInit, taux, duree):
    return taux * montantInit * (((1+taux)**(12*duree)) / (((1+taux)**(12*duree)) - 1))

'''
    affiche les informations mensuel relatives au remboursement de 'montantInit'
    avec un taux mensuel 'tauxMensuel'
    sur une durée de 'duree' ans.
    ainsi que le somme des interets cummulés de l'emprunt
'''
def affichageRemboursement(montantInit, tauxMensuel, duree):
    delta = remboursementMens(montantInit, tauxMensuel, duree)
    sommeInteret = 0
    for mois in range(12*duree+1):
        # reste à payer en fonction du mois
        reste = (montantInit * ((1+tauxMensuel)**mois)) - delta * ((((1+tauxMensuel)**mois) -1) / (tauxMensuel))

        # somme des interets:
        interet = reste * tauxMensuel
        sommeInteret = sommeInteret + interet

        # affichage des informations mensuel
        print("mois n°" + str(mois) + "\t reste: " + str(round(reste,3)) + "\t interets: " + str(round(interet,3)) + "\t interets cumulés: " + str(round(sommeInteret,3)))
    print("total des interets cummulés: " + str(round(sommeInteret,3)))
    print("dépenses total: " + str(round(montantInit + sommeInteret,3)))
    
'''
    affiche le montant qu'il reste à payer au kieme mois avec un taux normal
'''
def demi(N , ra , p0):
    r = ra/12
    mens = r * p0 *( (1+r) ** (12*N) ) / (( (1+r) ** (12 * N) ) - 1 )
    
    for k in range(0,12*N+1):
        pk = p0*(1+r)**k - mens * (((1+r) ** k )- 1)/r
        print (k,round(pk,3))
        if(pk <= (p0/2)):
            print ("la moitié est remboursé")

'''
    affiche le montant qu'il reste à payer au kieme mois avec un taux effectif
'''
def demiEffectif(N , ra , p0):
    r = ((1+ra/12)**12-1)/12
    mens = r * p0 *( (1+r) ** (12*N) ) / (( (1+r) ** (12 * N) ) - 1 )
    for k in range(0,12*N+1):
        pk = p0*(1+r)**k - mens * (((1+r) ** k )- 1)/r
        print (k,round(pk,3))
        if(pk <= (p0/2)):
            print ("la moitié est remboursé")
            
'''
    retourne le montant qu'il reste à payer au Nieme mois avec un taux effectif
'''
def demi2(N , ra , p0):
    r = ((1+ra/12)**12-1)/12
    #r = ra/12
    mens = r * p0 *( (1+r) ** (240) ) / (( (1+r) ** (240) ) - 1 )
    return p0*(1+r)**N - mens * (((1+r) ** N )- 1)/r
    
'''
    exemple de création d'un graphique en php
'''
def graphique():
    x = np.linspace(1,240,50)
    plt.plot(x, demi2(x,0.05,40000) , "b", label='sement')
    plt.plot([1,240], [40000,0],"r", label="linéaire") 
    plt.xlabel("Mois")
    plt.ylabel("Montant")
    plt.show()
    
'''
    génère l'apendice sous forme d'un tableau à 2 dimensions
'''
def apendice(periode, montant, taux):
    rm = exp( log( 1 + (taux/2) ) / 6 ) - 1;
    res = rm * montant * ( ( (1+rm)** (12 * periode) ) / ( (1+rm) ** (12 * periode ) - 1 ))
    return res


'''
    génère la table d'apendice  pour un taux donné jusqu'à un amortissement de 25 ans
'''
def table(taux):
    listePeriode = []
    for k in range(1, 25):
        listePeriode.append(k)
    
    listeMontant = []
    for k in range(1, 10 ):
        listeMontant.append(k * 1000)

    tableApendice = []
    lignes, colonnes = 9, 24
    tableApendice = [[0] * colonnes] * lignes
    
    for m in range(0, 9):
        for p in range(0, 24):
            tableApendice.append( apendice(listePeriode[p] , listeMontant[m] , taux) )
    return tableApendice
    
