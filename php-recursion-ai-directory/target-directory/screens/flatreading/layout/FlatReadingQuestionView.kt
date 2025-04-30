package com.rzrasel.wordquiz.presentation.screens.flatreading.layout

import androidx.compose.foundation.Image
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.size
import androidx.compose.foundation.layout.width
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.graphics.painter.Painter
import androidx.compose.ui.graphics.vector.ImageVector
import androidx.compose.ui.layout.ContentScale
import androidx.compose.ui.res.painterResource
import androidx.compose.ui.res.vectorResource
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.core.graphics.toColorInt
import com.rzrasel.wordquiz.R
import com.rzrasel.wordquiz.presentation.components.components.RoundedCornerChip
import com.rzrasel.wordquiz.presentation.screens.flatreading.cleanarchitecture.data.FlatReadingQuestionDataModel

@Composable
fun FlatReadingQuestionView(index: Int, question: FlatReadingQuestionDataModel) {
    Text(
        text = question.question!!,
        fontWeight = FontWeight.Bold,
    )
    Spacer(Modifier.height(16.dp))
    RoundedCornerChip(
        modifier = Modifier
            .fillMaxWidth()
            .background(Color("#f3f3f8".toColorInt()))
            .padding(0.dp),
        contentModifier = Modifier
            .fillMaxWidth()
            .background(Color("#f3f3f8".toColorInt()))
            .padding(8.dp),
        cornerRadius = 6,
        //backgroundColor = Color.Blue,
    ) {
        Column(
            modifier = Modifier
                .fillMaxWidth(),
        ) {
            question.answerSet.forEach { item ->
                var imageVector = ImageVector.vectorResource(R.drawable.icon_checkbox_unchecked_outline)
                var painter: Painter = painterResource(R.drawable.icon_checkbox_unchecked_outline)
                if(item.isTure) {
                    imageVector = ImageVector.vectorResource(R.drawable.icon_checkbox_checked_filled)
                    painter = painterResource(R.drawable.icon_checkbox_checked_filled)
                }
                Row(
                    modifier = Modifier
                        .padding(4.dp),
                ) {
                    /*Icon(
                        imageVector = imageVector,
                        contentDescription = "Check Box",
                        //tint = Color.Blue,
                        modifier = Modifier
                            .size(118.dp)
                            .rotate(0f) // No rotation applied.
                            .scale(0f)  // Scales the icon to 3 times its size.
                    )*/
                    Image(
                        painter,
                        contentDescription = "",
                        contentScale = ContentScale.FillBounds,
                        modifier = Modifier
                            .size(22.dp),
                    )
                    Spacer(modifier = Modifier.width(6.dp))
                    Text(
                        text = item.answer!!,
                        color = Color("#232323".toColorInt())
                    )
                }
            }
        }
    }
}