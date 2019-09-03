# -*- coding: utf-8 -*-
"""
Created on Thu Feb  7 14:08:32 2019

@author: j5310
"""

import numpy as np
import matplotlib.pyplot as plt
        
def Seiki(theta):
    return 1.0/np.sqrt(2*np.pi)*np.exp(-theta**2/2)

def SeikiBunpu(l,h,d):	
    return [Seiki(theta)*d for theta in np.arange(l,h+d,d)]

def Logistic(theta, a, b):
    return 1.0/(1.0+np.exp(-1.7*a*(theta-b)))

def SeitouKakuritsu(x, theta, a, b):
    P = Logistic(theta, a, b)
    return P**x*(1.0-P)**(1-x)

def ICC(x, a, b, l,h,d):
	return [SeitouKakuritsu(x, theta, a, b) for theta in np.arange(l,h+d,d)]

def Bayes(X, A, B, l,h,d):
    jizen = SeikiBunpu(l,h,d)
    #jizen = np.log(jizen)
    
    for i in range(len(X)):
        yuudo = ICC(X[i],A[i],B[i],l,h,d)
        #yuudo = np.log(yuudo)
        jigo = np.array(yuudo) * np.array(jizen)
        #jigo = np.array(yuudo) + np.array(jizen)
        jizen = jigo.copy()
    
    jizen /= np.sum(jizen)
    return jizen

def Estimation(X, A, B, l,h,d):
    Theta = np.arange(l,h+d,d)
    
    probability = Bayes(X, A, B, l,h,d)
    
    theta = Theta[np.argmax(probability)]
    error = Error(theta, A, B)
    return {"theta":theta, "error":error}

def Information(theta, A, B):
    Ps = np.array([Logistic(theta,A[i],B[i]) for i in range(len(A))])
    return np.sum(1.7**2*np.array(A)**2*Ps*(1.0-Ps))

def Error(theta,A,B):
    return 1.0/np.sqrt(Information(theta,A,B))



#上記のソースコードを実装し，結果をブラウザ上に出力してください．
def practice0():
    print(np.arange(-4,4.1,0.1))

def practice1():
    d =  0.1
    l = -3.0
    h =  3.0
    plt.title("Normal distribution")
    plt.xlabel("X")
    plt.xlim(l,h)
    plt.ylabel("Probability")
    plt.plot(np.arange(l,h+d,d),SeikiBunpu(l,h,d))
    plt.show()

def practice2():
    d =  0.1
    l = -3.0
    h =  3.0
    
    plt.figure("practice2")
    plt.xlabel("Ability")
    plt.xlim(l,h)
    plt.ylim(0,1)
    plt.ylabel("Correct probability")
    plt.plot(np.arange(l,h+d,d),ICC(1,1,0, l,h,d))
    plt.show()

def practice3():
    
    d =  0.1
    l = -3.0
    h =  3.0

    B = [0.0, 1.0, 0.5]
    A = [2.0, 1.0, 0.5]
    X = [1, 0, 1]
    
    print(Estimation(X,A,B,l,h,d))

def simulation():
    
    d =  0.01
    l = -3.0
    h =  3.0
    
    I = 10
    A = np.exp(np.random.normal( 0.43, 0.20, I))
    B = np.random.normal(-0.20, 1.16, I)
    
    Theta = np.arange(l,h+d,d)
    
    info = [Information(theta,A,B) for theta in Theta]
    plt.figure("test_information",figsize=(8,4))
    plt.title("Test information")
    plt.xlabel("Ability")
    plt.xlim(l,h)
    #plt.ylim(ymin=0,ymax=np.max(info)*1.1)
    plt.ylabel("Information")
    plt.bar(Theta,info)
    plt.show()
    
    J = 10
    jukensha = np.random.normal(0,1,J)
    jukensha.sort()
    
    gosa = []
    for theta in jukensha:
        X = []
        for i in range(len(A)):
            a = A[i]
            b = B[i]
            X.append(1) if SeitouKakuritsu(1,theta,a,b) > np.random.rand() else X.append(0)
        result = Estimation(X,A,B,l,h,d)
        gosa.append(abs(theta-result["theta"]))
    print("最大誤差:", np.mean(gosa))
    print(jukensha)

def test_information():
    
    import json
    import codecs
    A = []
    B = []
    with codecs.open("kamaya.json","r","utf-8") as f:
        data = json.load(f)
        for d in data:
            A.append(d["a"])
            B.append(d["b"])
    
        d =  1
        l = -4.0
        h =  4.0
        
        Theta = np.arange(l,h+d,d)
        
        info = [Information(theta,A,B) for theta in Theta]
        plt.figure("test_information",figsize=(12,4))
        plt.title("Test information")
        plt.xlabel("Ability")
        plt.xlim(-4,4)
        #plt.ylim(ymin=0,ymax=np.max(info)*1.1)
        plt.ylabel("Information")
        plt.bar(Theta,info)
        plt.show()
    

if __name__ == '__main__':
    simulation()
    #test_information()