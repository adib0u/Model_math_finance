import numpy as np
import matplotlib.pyplot as plt

def demi(N , ra , p0):
    r = ra/12
    mens = r * p0 *( (1+r) ** (12*N) ) / (( (1+r) ** (12 * N) ) - 1 )
    
    for k in range(0,12*N+1):
        pk = p0*(1+r)**k - mens * (((1+r) ** k )- 1)/r
        print (k,round(pk,3))
        if(pk <= (p0/2)):
            print ("la moitié est remboursé")
            
def demiEffectif(N , ra , p0):
    r = ((1+ra/12)**12-1)/12
    mens = r * p0 *( (1+r) ** (12*N) ) / (( (1+r) ** (12 * N) ) - 1 )
    for k in range(0,12*N+1):
        pk = p0*(1+r)**k - mens * (((1+r) ** k )- 1)/r
        print (k,round(pk,3))
        if(pk <= (p0/2)):
            print ("la moitié est remboursé")
            
def demi2(N , ra , p0):
    r = ((1+ra/12)**12-1)/12
    #r = ra/12
    mens = r * p0 *( (1+r) ** (240) ) / (( (1+r) ** (240) ) - 1 )
    return p0*(1+r)**N - mens * (((1+r) ** N )- 1)/r
    
    
def graphique():
    x = np.linspace(1,240,50)
    plt.plot(x, demi2(x,0.05,40000) , "b", label='sement')
    plt.plot([1,240], [40000,0],"r", label="linéaire") 
    plt.xlabel("Mois")
    plt.ylabel("Montant")
    plt.show()
        
