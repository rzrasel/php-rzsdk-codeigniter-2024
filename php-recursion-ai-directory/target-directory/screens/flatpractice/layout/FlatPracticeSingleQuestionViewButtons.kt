package com.rzrasel.wordquiz.presentation.screens.flatpractice.layout

import android.content.Context
import android.widget.Toast
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.size
import androidx.compose.runtime.Composable
import androidx.compose.runtime.MutableState
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.core.graphics.toColorInt
import com.rzrasel.wordquiz.presentation.components.components.ButtonComposable

@Composable
fun FlatPracticeSingleQuestionViewButtons(
    currentIndex: MutableState<Int>,
    totalQuestion: Int,
    onClickSubmit: (Int)-> Unit,
) {
    val context: Context = LocalContext.current
    Row(
        modifier = Modifier
            .fillMaxWidth(),
        horizontalArrangement = Arrangement.SpaceBetween
    ) {
        if(currentIndex.value > 0) {
            ButtonComposable(
                backgroundColor = Color("#de7b73".toColorInt()),
                contentColor = Color("#ffffff".toColorInt()),
                fontWeight = FontWeight.Bold,
                cornerRadius = 6.dp,
                text = "Previous",
                onClick = {
                    val previousIndex = currentIndex.value - 1
                    if (previousIndex < 0) {
                        Toast.makeText(context, "Go for previous", Toast.LENGTH_LONG).show()
                    } else {
                        currentIndex.value = previousIndex
                    }
                }
            )
        } else {
            Spacer(modifier = Modifier.size(10.dp))
        }
        if(currentIndex.value < totalQuestion - 1) {
            ButtonComposable(
                backgroundColor = Color("#239cbc".toColorInt()),
                contentColor = Color("#e9fcff".toColorInt()),
                fontWeight = FontWeight.Bold,
                cornerRadius = 6.dp,
                text = "Next",
                onClick = {
                    val nextIndex = currentIndex.value + 1
                    if (nextIndex >= totalQuestion) {
                        Toast.makeText(context, "Go for next", Toast.LENGTH_LONG).show()
                    } else {
                        currentIndex.value = nextIndex
                    }
                    //Toast.makeText(context, "Coming soon $nextIndex - ${flatReadingModel.questionSet.size}", Toast.LENGTH_LONG).show()
                }
            )
        } else {
            ButtonComposable(
                backgroundColor = Color("#239cbc".toColorInt()),
                contentColor = Color("#e9fcff".toColorInt()),
                fontWeight = FontWeight.Bold,
                cornerRadius = 6.dp,
                text = "Submit",
                onClick = {
                    /*val nextIndex = currentIndex.value + 1
                    if (nextIndex >= totalQuestion) {
                        Toast.makeText(context, "Go for submit", Toast.LENGTH_LONG).show()
                    } else {
                        currentIndex.value = nextIndex
                    }*/
                    //Toast.makeText(context, "Coming soon $nextIndex - ${flatReadingModel.questionSet.size}", Toast.LENGTH_LONG).show()
                    onClickSubmit(currentIndex.value)
                }
            )
        }
    }
}