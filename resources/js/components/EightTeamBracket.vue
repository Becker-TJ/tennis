<template>
<div>
    <table id="bracket">
        <tr>
            <th>Consolation Champion</th>
            <th>Consolation Final</th>
            <th>Consolation Semis</th>
            <th>First Round</th>
            <th>Winner's Semis</th>
            <th>Winner's Final</th>
            <th>Champion</th>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td id="matchup-1-top" @click="saveResult($event)">
                {{ bracketData['bracketPositions']['matchup-1-top']['firstAndLastName'] }}
            </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td id="matchup-1-top-school" class="give-left-border give-top-border give-right-border">
                {{ bracketData['bracketPositions']['matchup-1-top']['school_name'] }}
            </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td id="matchup-9-top" @click="saveResult($event)">
                {{ displayPlayerSchool('matchup-9-top') }}
            </td>
            <td class="give-left-border give-right-border">
                <select id="matchup-1-court" @change="saveCourt($event)">
                    <option>Waiting For Court</option>
                    <option v-for="courtNumber in bracketData['courtCount']"
                            :key="courtNumber"
                            :value="courtNumber"
                            :disabled="isCourtInUse(courtNumber)"
                            :selected="isCourtSelected(courtNumber, 1)"
                    >Court {{ courtNumber }}</option>
                </select>
            </td>
            <td id="matchup-5-top" @click="saveResult($event)">
                {{ displayPlayerSchool('matchup-5-top') }}
            </td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td class="give-top-border give-left-border"></td>
            <td id="matchup-1-bottom" @click="saveResult($event)" class="give-left-border give-right-border">
                {{ bracketData['bracketPositions']['matchup-1-bottom']['firstAndLastName'] }}
            </td>
            <td class="give-top-border give-right-border">
                <input v-if="matchupComplete(1)"
                       @keydown="validateScore($event)"
                       class="score-input" type="text"
                       id="matchup-1-score"
                       :value="getScore(1)"
                >
            </td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td class="give-left-border"></td>
            <td id="matchup-1-bottom-school" class="give-top-border">
                {{ bracketData['bracketPositions']['matchup-1-bottom']['school_name'] }}
            </td>
            <td class="give-right-border"></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td id="matchup-11-top" @click="saveResult($event)">
                {{ displayPlayerSchool('matchup-11-top') }}
            </td>
            <td class="give-left-border">
                <select id="matchup-9-court" @change="saveCourt($event)">
                    <option>Waiting For Court</option>
                    <option v-for="courtNumber in bracketData['courtCount']"
                            :key="courtNumber"
                            :value="courtNumber"
                            :disabled="isCourtInUse(courtNumber)"
                            :selected="isCourtSelected(courtNumber, 9)"
                    >Court {{ courtNumber }}</option>
                </select>
            </td>
            <td></td>
            <td class="give-right-border">
                <select id="matchup-5-court" @change="saveCourt($event)">
                    <option>Waiting For Court</option>
                    <option v-for="courtNumber in bracketData['courtCount']"
                            :key="courtNumber"
                            :value="courtNumber"
                            :disabled="isCourtInUse(courtNumber)"
                            :selected="isCourtSelected(courtNumber, 5)"
                    >Court {{ courtNumber }}</option>
                </select>
            </td>
            <td id="matchup-7-top" @click="saveResult($event)">
                {{ displayPlayerSchool('matchup-7-top') }}
            </td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td class="give-top-border give-left-border">
                <input v-if="matchupComplete(9)"
                       class="score-input"
                       type="text"
                       id="matchup-9-score"
                       :value="getScore(9)"
                >
            </td>
            <td class="give-left-border"></td>
            <td></td>
            <td class="give-right-border"></td>
            <td class="give-top-border give-right-border">
                <input v-if="matchupComplete(5)"
                       class="score-input"
                       type="text"
                       id="matchup-5-score"
                       :value="getScore(5)"
                >
            </td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td class="give-left-border"></td>
            <td class="give-left-border"></td>
            <td id="matchup-2-top" @click="saveResult($event)">
                {{ bracketData['bracketPositions']['matchup-2-top']['firstAndLastName'] }}
            </td>
            <td class="give-right-border"></td>
            <td class="give-right-border"></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td class="give-left-border"></td>
            <td id="matchup-9-bottom" class="give-left-border" @click="saveResult($event)">
                {{ displayPlayerSchool('matchup-9-bottom') }}
            </td>
            <td id="matchup-2-top-school" class="give-left-border give-right-border give-top-border">
                {{ bracketData['bracketPositions']['matchup-2-top']['school_name'] }}
            </td>
            <td id="matchup-5-bottom" class="give-right-border" @click="saveResult($event)">
                {{ displayPlayerSchool('matchup-5-bottom') }}
            </td>
            <td class="give-right-border"></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td class="give-left-border"></td>
            <td class="give-top-border"></td>
            <td class="give-left-border give-right-border">
                <select id="matchup-2-court" @change="saveCourt($event)">
                    <option>Waiting For Court</option>
                    <option v-for="courtNumber in bracketData['courtCount']"
                            :key="courtNumber"
                            :value="courtNumber"
                            :disabled="isCourtInUse(courtNumber)"
                            :selected="isCourtSelected(courtNumber, 2)"
                    >Court {{ courtNumber }}</option>
                </select>
            </td>
            <td class="give-top-border">
                <input v-if="matchupComplete(2)"
                       class="score-input"
                       type="text"
                       id="matchup-2-score"
                       :value="getScore(2)"
                >
            </td>
            <td class="give-right-border"></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td class="give-left-border"></td>
            <td></td>
            <td id="matchup-2-bottom" @click="saveResult($event)" class="give-left-border give-right-border">
                {{ bracketData['bracketPositions']['matchup-2-bottom']['firstAndLastName'] }}
            </td>
            <td></td>
            <td class="give-right-border"></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td class="give-left-border"></td>
            <td></td>
            <td id="matchup-2-bottom-school" class="give-top-border">
                {{ bracketData['bracketPositions']['matchup-2-bottom']['school_name'] }}
            </td>
            <td></td>
            <td class="give-right-border"></td>
            <td></td>
        </tr>
        <tr>
            <td id="fifth-place">
                {{ displayPlayerSchool('fifth-place') }}
            </td>
            <td class="give-left-border">
                <select id="matchup-11-court" @change="saveCourt($event)">
                    <option>Waiting For Court</option>
                    <option v-for="courtNumber in bracketData['courtCount']"
                            :key="courtNumber"
                            :value="courtNumber"
                            :disabled="isCourtInUse(courtNumber)"
                            :selected="isCourtSelected(courtNumber, 11)"
                    >Court {{ courtNumber }}</option>
                </select>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td class="give-right-border">
                <select id="matchup-7-court" @change="saveCourt($event)">
                    <option>Waiting For Court</option>
                    <option v-for="courtNumber in bracketData['courtCount']"
                            :key="courtNumber"
                            :value="courtNumber"
                            :disabled="isCourtInUse(courtNumber)"
                            :selected="isCourtSelected(courtNumber, 7)"
                    >Court {{ courtNumber }}</option>
                </select>
            </td>
            <td id="champion">
                {{ displayPlayerSchool('champion') }}
            </td>
        </tr>
        <tr>
            <td class="give-top-border">
                <input v-if="matchupComplete(11)"
                       class="score-input"
                       type="text"
                       id="matchup-11-score"
                       :value="getScore(11)"
                >
            </td>
            <td class="give-left-border"></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="give-right-border"></td>
            <td class="give-top-border">
                <input v-if="matchupComplete(7)"
                       class="score-input"
                       type="text"
                       id="matchup-7-score"
                       :value="getScore(7)"
                >
            </td>
            </td>
        </tr>
        <tr>
            <td></td>
            <td class="give-left-border"></td>
            <td></td>
            <td id="matchup-3-top" @click="saveResult($event)">
                {{ bracketData['bracketPositions']['matchup-3-top']['firstAndLastName'] }}
            </td>
            <td></td>
            <td class="give-right-border"></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td class="give-left-border"></td>
            <td></td>
            <td id="matchup-3-top-school" class="give-left-border give-top-border give-right-border">
                {{ bracketData['bracketPositions']['matchup-3-top']['school_name'] }}
            </td>
            <td></td>
            <td class="give-right-border"></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td class="give-left-border"></td>
            <td id="matchup-10-top" @click="saveResult($event)">
                {{ displayPlayerSchool('matchup-10-top') }}
            </td>
            <td class="give-left-border give-right-border">
                <select id="matchup-3-court" @change="saveCourt($event)">
                    <option>Waiting For Court</option>
                    <option v-for="courtNumber in bracketData['courtCount']"
                            :key="courtNumber"
                            :value="courtNumber"
                            :disabled="isCourtInUse(courtNumber)"
                            :selected="isCourtSelected(courtNumber, 3)"
                    >Court {{ courtNumber }}</option>
                </select>
            </td>
            <td id="matchup-6-top" @click="saveResult($event)">
                {{ displayPlayerSchool('matchup-6-top') }}
            </td>
            <td class="give-right-border"></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td class="give-left-border"></td>
            <td class="give-top-border give-left-border"></td>
            <td id="matchup-3-bottom" @click="saveResult($event)" class="give-left-border give-right-border">
                {{ bracketData['bracketPositions']['matchup-3-bottom']['firstAndLastName'] }}
            </td>
            <td class="give-top-border give-right-border">
                <input v-if="matchupComplete(3)"
                       class="score-input"
                       type="text"
                       id="matchup-3-score"
                       :value="getScore(3)"
                >
            </td>
            <td class="give-right-border"></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td class="give-left-border"></td>
            <td class="give-left-border"></td>
            <td id="matchup-3-bottom-school" class="give-top-border">
                {{ bracketData['bracketPositions']['matchup-3-bottom']['school_name'] }}
            </td>
            <td class="give-right-border"></td>
            <td class="give-right-border"></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td id="matchup-11-bottom" class="give-left-border" @click="saveResult($event)">
                {{ displayPlayerSchool('matchup-11-bottom') }}
            </td>
            <td class="give-left-border">
                <select id="matchup-10-court" @change="saveCourt($event)">
                    <option>Waiting For Court</option>
                    <option v-for="courtNumber in bracketData['courtCount']"
                            :key="courtNumber"
                            :value="courtNumber"
                            :disabled="isCourtInUse(courtNumber)"
                            :selected="isCourtSelected(courtNumber, 10)"
                    >Court {{ courtNumber }}</option>
                </select>
            </td>
            <td></td>
            <td class="give-right-border">
                <select id="matchup-6-court" @change="saveCourt($event)">
                    <option>Waiting For Court</option>
                    <option v-for="courtNumber in bracketData['courtCount']"
                            :key="courtNumber"
                            :value="courtNumber"
                            :disabled="isCourtInUse(courtNumber)"
                            :selected="isCourtSelected(courtNumber, 6)"
                    >Court {{ courtNumber }}</option>
                </select>
            </td>
            <td id="matchup-7-bottom" class="give-right-border" @click="saveResult($event)">
                {{ displayPlayerSchool('matchup-7-bottom') }}
            </td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td class="give-top-border">
                <input v-if="matchupComplete(10)"
                       class="score-input"
                       type="text"
                       id="matchup-10-score"
                       :value="getScore(10)"
                >
            </td>
            <td class="give-left-border"></td>
            <td></td>
            <td class="give-right-border"></td>
            <td class="give-top-border">
                <input v-if="matchupComplete(6)"
                       class="score-input"
                       type="text"
                       id="matchup-6-score"
                       :value="getScore(6)"
                >
            </td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td class="give-left-border"></td>
            <td id="matchup-4-top" @click="saveResult($event)">
                {{ bracketData['bracketPositions']['matchup-4-top']['firstAndLastName'] }}
            </td>
            <td class="give-right-border"></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td id="matchup-10-bottom" class="give-left-border" @click="saveResult($event)">
                {{ displayPlayerSchool('matchup-10-bottom') }}
            </td>
            <td id="matchup-4-top-school" class="give-top-border give-right-border give-left-border">
                {{ bracketData['bracketPositions']['matchup-4-top']['school_name'] }}
            </td>
            <td id="matchup-6-bottom" class="give-right-border" @click="saveResult($event)">
                {{ displayPlayerSchool('matchup-6-bottom') }}
            </td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td class="give-top-border"></td>
            <td class="give-left-border give-right-border">
                <select id="matchup-4-court" @change="saveCourt($event)">
                    <option>Waiting For Court</option>
                    <option v-for="courtNumber in bracketData['courtCount']"
                            :key="courtNumber"
                            :value="courtNumber"
                            :disabled="isCourtInUse(courtNumber)"
                            :selected="isCourtSelected(courtNumber, 4)"
                    >Court {{ courtNumber }}</option>
                </select>
            </td>
            <td class="give-top-border">
                <input v-if="matchupComplete(4)"
                       class="score-input"
                       type="text"
                       id="matchup-4-score"
                       :value="getScore(4)"
                >
            </td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td id="matchup-4-bottom" @click="saveResult($event)" class="give-left-border give-right-border">
                {{ bracketData['bracketPositions']['matchup-4-bottom']['firstAndLastName'] }}
            </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td id="matchup-4-bottom-school" class="give-top-border">
                {{ bracketData['bracketPositions']['matchup-4-bottom']['school_name'] }}
            </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <table id="bracket">
            <tr>
                <th></th>
                <th>Seventh Place</th>
                <th></th>
                <th></th>
                <th></th>
                <th>Third Place</th>
                <th></th>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td id="matchup-12-top" @click="saveResult($event)">
                    {{ displayPlayerSchool('matchup-12-top') }}
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td id="matchup-8-top" @click="saveResult($event)">
                    {{ displayPlayerSchool('matchup-8-top') }}
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="give-top-border give-left-border"></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="give-top-border give-right-border"></td>
                <td></td>
            </tr>
            <tr>
                <td id="seventh-place">
                    {{ displayPlayerSchool('seventh-place') }}
                </td>
                <td class="give-left-border">
                    <select id="matchup-12-court" @change="saveCourt($event)">
                        <option>Waiting For Court</option>
                        <option v-for="courtNumber in bracketData['courtCount']"
                                :key="courtNumber"
                                :value="courtNumber"
                                :disabled="isCourtInUse(courtNumber)"
                                :selected="isCourtSelected(courtNumber, 12)"
                        >Court {{ courtNumber }}</option>
                    </select>
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td class="give-right-border">
                    <select id="matchup-8-court" @change="saveCourt($event)">
                        <option>Waiting For Court</option>
                        <option v-for="courtNumber in bracketData['courtCount']"
                                :key="courtNumber"
                                :value="courtNumber"
                                :disabled="isCourtInUse(courtNumber)"
                                :selected="isCourtSelected(courtNumber, 8)"
                        >Court {{ courtNumber }}</option>
                    </select>
                </td>
                <td id="third-place">
                    {{ displayPlayerSchool('third-place') }}
                </td>
            </tr>
            <tr>
                <td class="give-top-border">
                    <input v-if="matchupComplete(12)"
                           class="score-input"
                           type="text"
                           id="matchup-12-score"
                           :value="getScore(12)"
                    >
                </td>
                <td class="give-left-border"></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="give-right-border"></td>
                <td class="give-top-border">
                    <input v-if="matchupComplete(8)"
                           class="score-input"
                           type="text"
                           id="matchup-8-score"
                           :value="getScore(8)"
                    >
                </td>
            </tr>
            <tr>
                <td></td>
                <td id="matchup-12-bottom" class="give-left-border" @click="saveResult($event)">
                    {{ displayPlayerSchool('matchup-12-bottom') }}
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td id="matchup-8-bottom" class="give-right-border" @click="saveResult($event)">
                    {{ displayPlayerSchool('matchup-8-bottom') }}
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="give-top-border"></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="give-top-border"></td>
                <td></td>
            </tr>
        </table>
    </table>
</div>
</template>

<script>
import axios from 'axios';
export default {
    name: "EightTeamBracket",
    props: ['tournament'],
    data(){
        return {
            getBracketDataRoute: '/getBracketData',
            saveResultRoute: '/saveResult',
            saveCourtRoute: '/saveCourt',
            bracketData: {},
            selectedBracket: 'girlsOneSingles',
            validScoresAfterOneThroughFour: [6],
            validScoresAfterFive: [7],
            validScoresAfterSix: [0,1,2,3,4,7],
            validScoresAfterSeven: [5,6],
        }
    },
    mounted() {
        this.getBracketData();
    },
    methods: {
        getBracketData() {
            let params = {
                'tournament_id': this.tournament.id,
                'requestedBracket': 'girlsOneSingles'
            };
            axios.post(this.getBracketDataRoute, { ...params })
                .then(response => {
                    this.bracketData = response.data;
                    console.log('This is good');
                })
                .catch(error => {
                    console.error(error);
                });
        },
        saveResult(event) {
            const winnerFieldId = event.target.id;
            const winningPlayer = this.bracketData['bracketPositions'][winnerFieldId];

            const loserFieldId = this.getLoserFieldId(winnerFieldId);
            const losingPlayer = this.bracketData['bracketPositions'][loserFieldId];

            const matchupNumber = this.getMatchupNumber(winnerFieldId);

            let params = {
                bracket: 'girlsOneSingles',
                winningPlayer: winningPlayer,
                losingPlayer: losingPlayer,
                matchupNumber: matchupNumber,
                score: '6-4, 6-4',
                tournament_id: this.tournament.id,
            };

            axios.post(this.saveResultRoute, { ...params })
                .then(response => {
                    this.saveResultData = response.data;
                    this.getBracketData();
                })
                .catch(error => {
                    console.error(error);
                });
        },
        getLoserFieldId(winnerFieldId) {
            let loserFieldId = winnerFieldId.replace('top', 'bottom');
            if (loserFieldId !== winnerFieldId) {
                return loserFieldId;
            }

            return winnerFieldId.replace('bottom', 'top');
        },

        getMatchupNumber(fieldId) {
            const matches = fieldId.match(/\d+/);

            if (matches) {
                return parseInt(matches[0], 10);
            }

            return null;
        },

        matchupComplete(matchupNumber) {
            return this.bracketData.matches.some(match => match.matchup === matchupNumber)
        },

        displayPlayerSchool(matchupFieldId) {
            if (matchupFieldId in this.bracketData['bracketPositions']) {
                return this.bracketData['bracketPositions'][matchupFieldId]['school_name'];
            } else {
                return '';
            }
        },

        saveCourt($event) {
            const courtSelection = $event.target.value;
            const matchup = this.getMatchupNumber(event.target.id);

            let params = {
                matchup: matchup,
                courtSelection: courtSelection,
                tournament_id: this.tournament.id,
                bracket: 'girlsOneSingles'
            };

            axios.post(this.saveCourtRoute, { ...params })
                .then(response => {
                    this.getBracketData();
                })
                .catch(error => {
                    console.error(error);
                });
        },

        isCourtInUse(courtNumber) {
            return this.bracketData['courtsInUse'].some(
                courtObj => courtObj.court === courtNumber
            );
        },

        isCourtSelected(courtNumber, matchup) {
            return this.bracketData['courtsInUse'].some(
                courtObj => (
                    courtObj.court === courtNumber &&
                    courtObj.matchup === matchup &&
                    courtObj.bracket === this.selectedBracket
                )
            );
        },

        validateScore($event) {
            //TODO FIX
            //working outside of tiebreak scores, but could use refactoring.
            $event.preventDefault();
            const scoreInputId = $event.target.id;
            const matchup = this.getMatchupNumber(scoreInputId);
            let keyPressed = $event.key;
            let checkScoreValidity = false;

            const match = this.bracketData['matches'].find(match => match.matchup === matchup);

            if(keyPressed === 'Backspace' || keyPressed === 'Delete') {
                match.score = '';
            }

            const regex = /^[0-9]$/;
            if (!regex.test(keyPressed)) {
                return;
            }

            let numberPressed = parseInt(keyPressed);

            let firstScore = parseInt(match.score.charAt(0));
            let secondScore = parseInt(match.score.charAt(2));
            let thirdScore = parseInt(match.score.charAt(5));
            let fourthScore = parseInt(match.score.charAt(7));

            if(match.score.length === 0) {
                const firstScoreRegex = /^[0-7]$/;
                if(firstScoreRegex.test(keyPressed)) {
                    match.score = numberPressed + '-';
                }
            }

            else if (match.score.length === 2) {
                if(firstScore === 7) {
                    if(this.validScoresAfterSeven.includes(numberPressed)) {
                        match.score += numberPressed;
                    }
                }
                else if(firstScore === 6) {
                    if(this.validScoresAfterSix.includes(numberPressed)) {
                        match.score += numberPressed;
                    }
                } else if(firstScore === 5) {
                    if(this.validScoresAfterFive.includes(numberPressed)) {
                        match.score += numberPressed;
                    }
                } else if(firstScore < 5) {
                    if(this.validScoresAfterOneThroughFour.includes(numberPressed)) {
                        match.score += numberPressed;
                    }
                }
            }

            else if (match.score.length === 3) {
                match.score += ', ' + numberPressed + '-';
            }

            else if (match.score.length === 7) {
                if(thirdScore === 7) {
                    if(this.validScoresAfterSeven.includes(numberPressed)) {
                        match.score += numberPressed;
                        checkScoreValidity = true;
                    }
                }
                else if(thirdScore === 6) {
                    if(this.validScoresAfterSix.includes(numberPressed)) {
                        match.score += numberPressed;
                        checkScoreValidity = true;
                    }
                } else if(thirdScore === 5) {
                    if(this.validScoresAfterFive.includes(numberPressed)) {
                        match.score += numberPressed;
                        checkScoreValidity = true;
                    }
                } else if(thirdScore < 5) {
                    if(this.validScoresAfterOneThroughFour.includes(numberPressed)) {
                        match.score += numberPressed;
                        checkScoreValidity = true;
                    }
                }
            }


            else if (match.score.length === 8) {
                if(numberPressed === 0) {
                    return false;
                }
                match.score += ', (' + numberPressed;
            }

            else if (match.score.length === 12) {
                let fifthScore = parseInt(match.score.substring(11,13));
                if(fifthScore !== 1 || numberPressed !== 0) {
                    //save score
                    let firstTiebreakScoreString = fifthScore.toString() + numberPressed.toString();
                    let secondTieBreakScore = parseInt(firstTiebreakScoreString) - 2;
                    match.score += numberPressed + '-' + secondTieBreakScore + ')';
                    alert(match.score);
                    return;
                }
                match.score += numberPressed + '-';
            }

            else if (match.score.length === 14) {
                match.score += numberPressed;
                checkScoreValidity = true;
            }

            else if (match.score.length === 15) {
                match.score += numberPressed;
                checkScoreValidity = true;
            }

            if(checkScoreValidity) {
                if(this.isScoreValid(match.score) === 'third set needed' || this.isScoreValid(match.score) === 'needs to finish score') {
                    return false;
                }

                if(this.isScoreValid(match.score)) {
                    if(match.score.length > 8) {
                        match.score += ')';
                    }

                    alert(match.score);
                    //save score

                } else {
                    if(match.score.length > 8) {
                        match.score += ')';
                    }
                }
            }

            return false;

        },

        isScoreValid(score) {
            let setsWon = 0;
            let firstScore = parseInt(score.charAt(0));
            let secondScore = parseInt(score.charAt(2));
            let thirdScore = parseInt(score.charAt(5));
            let fourthScore = parseInt(score.charAt(7));
            if(score.length > 8){
                let fifthScore = parseInt(score.substring(11,13));
                if(score.length === 15) {
                    let sixthScore = parseInt(score.charAt(14));
                } else if (score.length === 16) {
                    let sixthScore = parseInt(score.substring(14,16));
                }

                if(sixthScore + 2 > fifthScore) {
                    return false;
                }

                if((fifthScore > 10) && (sixthScore != (fifthScore - 2))) {
                    if(sixthScore.toString().length > 1) {
                        return false;
                    }
                    return 'needs to finish score';
                }

                if(fifthScore > sixthScore) {
                    setsWon++;
                }
            }

            if(firstScore > secondScore) {
                setsWon++;
            }
            if(thirdScore > fourthScore) {
                setsWon++;
            }

            if(setsWon === 2) {
                return true;

            } else if ($setsWon === 1) {
                return 'third set needed';
            }

            return false;
        },

        getScore(matchup) {
            const match = this.bracketData['matches'].find(match => match.matchup === matchup);
            return match.score;
        }
    }
}
</script>

<style scoped>

</style>
