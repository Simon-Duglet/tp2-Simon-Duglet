function characterSelected(element){
    let id = element.getAttribute("character-id")
    let myHeaders = new Headers();
    let init = { method: 'GET',
        headers: myHeaders,};
    fetch("http://localhost/api/characterinfo?id=" + id, init).then((res)=>{
        return res.json()
    }).then((data)=>{
        card = data.card
        character = data.character

        document.getElementsByClassName("character-resume")[0].innerHTML = card.resume
        document.getElementsByClassName("character-special1")[0].innerHTML = card.power_active_desc
        document.getElementsByClassName("character-special2")[0].innerHTML = card.power_passive_desc
        document.getElementsByClassName("stat-strength")[0].innerHTML = character.strength
        document.getElementsByClassName("stat-defense")[0].innerHTML = character.defense
        document.getElementsByClassName("stat-health")[0].innerHTML = character.health
        document.getElementById("newgameform-character_id").value = character.id

    })
    document.getElementsByClassName("selected-character")[0].setAttribute("class", "character-thumbnail")
    element.setAttribute("class", "character-thumbnail selected-character")
}