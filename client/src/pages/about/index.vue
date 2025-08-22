<script setup>
import { ref } from 'vue'

const board = ref(Array(9).fill(''))
const players = ['X', 'O']
const currentPlayer = ref(players[0])
const message = ref(`${currentPlayer.value}'s turn!`)
const someoneWon = ref(false)

const winning_combinations = [
    [0, 1, 2],
    [3, 4, 5],
    [6, 7, 8],
    [0, 3, 6],
    [1, 4, 7],
    [2, 5, 8],
    [0, 4, 8],
    [2, 4, 6]
]

function makeMove(index) {
    if (someoneWon.value || board.value[index] !== '') return

    board.value[index] = currentPlayer.value

    if (checkWin(currentPlayer.value)) {
        message.value = `Game over! ${currentPlayer.value} wins!`
        someoneWon.value = true
        return
    }

    if (checkTie()) {
        message.value = 'Game is tied!'
        someoneWon.value = true
        return
    }

    currentPlayer.value = currentPlayer.value === 'X' ? 'O' : 'X'
    message.value = `${currentPlayer.value}'s turn!`
}

function checkWin(player) {
    return winning_combinations.some(([a, b, c]) =>
        board.value[a] === player && board.value[b] === player && board.value[c] === player
    )
}

function checkTie() {
    return board.value.every(cell => cell !== '')
}

function restartButton() {
    board.value = Array(9).fill('')
    currentPlayer.value = players[0]
    message.value = `${currentPlayer.value}'s turn!`
    someoneWon.value = false
}
</script>

<template>
    <div class="flex flex-col items-center min-h-screen py-10 bg-gray-100">
        <h1 class="text-4xl font-bold text-gray-800 mb-8">This is a To Do List demo project. Have some Tic Tac Toe :^)</h1>
        <div class="grid grid-cols-3 gap-2">
            <div v-for="(square, index) in board" :key="index" @click="makeMove(index)"
                class="w-24 h-24 bg-white border-2 border-gray-400 flex items-center justify-center text-4xl font-bold cursor-pointer hover:bg-gray-200">
                {{ square }}
            </div>
        </div>

        <h2 class="mt-8 text-2xl font-semibold text-gray-700">{{ message }}</h2>

        <div class="mt-6">
            <button @click="restartButton"
                class="px-6 py-2 bg-yellow-400 hover:bg-yellow-500 text-yellow-900 font-semibold rounded-full border-2 border-yellow-800 transition-all duration-300">
                Restart
            </button>
        </div>
        <div class="hover: underline">
            From: <a href="https://github.com/canankorkut/TicTacToe">https://github.com/canankorkut/TicTacToe</a>
        </div>
    </div>
</template>
