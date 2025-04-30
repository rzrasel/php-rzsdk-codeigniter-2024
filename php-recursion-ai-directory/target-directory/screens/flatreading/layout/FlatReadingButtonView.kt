package com.rzrasel.wordquiz.presentation.screens.flatreading.layout

import android.content.Context
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
fun FlatReadingButtonView(
    currentIndex: MutableState<Int>,
    totalQuestion: Int,
    isSingleQuestionView: Boolean = true,
    onClickSubmit: (Int)-> Unit,
) {
    val context: Context = LocalContext.current
    if(isSingleQuestionView) {
        FlatReadingSingleQuestionViewButtons(
            currentIndex = currentIndex,
            totalQuestion = totalQuestion,
            onClickSubmit = onClickSubmit,
        )
    } else {
        Row(
            modifier = Modifier
                .fillMaxWidth(),
            horizontalArrangement = Arrangement.SpaceBetween
        ) {
            Spacer(modifier = Modifier.size(10.dp))
            ButtonComposable(
                backgroundColor = Color("#239cbc".toColorInt()),
                contentColor = Color("#e9fcff".toColorInt()),
                fontWeight = FontWeight.Bold,
                cornerRadius = 6.dp,
                text = "Submit",
                onClick = {
                    //Toast.makeText(context, "Go for Submit", Toast.LENGTH_LONG).show()
                    onClickSubmit(totalQuestion - 1)
                }
            )
        }
    }
}