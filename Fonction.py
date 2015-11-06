from math import exp, log

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
    Calcule l'annee pour laqu'elle le rendement annuel aura doublé
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