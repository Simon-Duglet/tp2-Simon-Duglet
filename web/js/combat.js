let csrfParam;
let csrfToken;
function initCsrf(p_csrfParam,p_csrfToken){
        csrfParam = p_csrfParam
        csrfToken = p_csrfToken
}
function hideMessage(){
    setTimeout(()=>{
        document.getElementsByClassName("message-container")[0].style.opacity = 0;
        setTimeout(()=>{
            document.getElementsByClassName("message-container")[0].style.display = "none";

        }, 3000);
    }, 3000);
}
function attack(btn){
    btn.disabled = true;
    setTimeout(()=>{
        btn.disabled = false

    }, 2100);
    let myHeaders = new Headers();
    let fd = new FormData();
    fd.append(csrfParam, csrfToken)
    let init = { method: 'post',
        headers: myHeaders,
        body: fd
    };
    fetch("http://localhost/combat-api/attack", init).then((res)=>{
        return res.json();
    }).then((data)=>{
        if (data.success){
            showCombatMessage(data);
            if (data.special_ready){
                document.getElementById("special-btn").disabled = false
            }
            else{
                document.getElementById("special-btn").disabled = true
            }
        }

        console.log(data)
    });
}
function specialAttack(btn){
    btn.disabled = true;
    let myHeaders = new Headers();
    let fd = new FormData();
    fd.append(csrfParam, csrfToken)
    let init = { method: 'post',
        headers: myHeaders,
        body: fd
    };
    fetch("http://localhost/combat-api/special-attack", init).then((res)=>{
        return res.json();
    }).then((data)=>{
        if (data.success){
            showCombatMessage(data);
        }
        console.log(data)
    });
}
function showCombatMessage(data){
    const message1 = data.message_given
    const message2 = data.message_taken
    document.getElementById("combat-message").innerHTML = message1;
    document.getElementById("combat-message").style.opacity = 1;
    document.getElementById("enemy-health").innerHTML = data.enemy.health
    document.getElementById("enemy-defense").innerHTML = data.enemy.defense
    document.getElementById("enemy-strength").innerHTML = data.enemy.strength
    setTimeout(()=>{
        document.getElementById("combat-message").style.opacity= 0
        setTimeout(()=>{
            document.getElementById("combat-message").innerHTML = message2;
            document.getElementById("combat-message").style.opacity= 1
            document.getElementById("player-health").innerHTML = data.player.health
            document.getElementById("player-defense").innerHTML = data.player.defense
            document.getElementById("player-strength").innerHTML = data.player.strength
            setTimeout(()=>{
                document.getElementById("combat-message").style.opacity= 0
                if (data.player_died){
                    setTimeout(()=>{
                        document.getElementById("combat-message").innerHTML = "Vous êtes mort...";
                        document.getElementById("combat-message").style.opacity= 1
                        document.getElementById("quit-btn").style.display = "block"
                    }, 1000);
                }
                else if(data.enemy_died){
                    setTimeout(()=>{
                        document.getElementById("combat-message").innerHTML = "Vous avez vaincu l'ennemi et gagné " + data.gold_reward + " or ! ";
                        document.getElementById("combat-message").style.opacity= 1
                        document.getElementById("quit-btn").style.display = "block"
                    }, 1000);
                }
            }, 2000);
        }, 1000);
    }, 2000);
}
