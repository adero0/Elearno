package com.ajea.Elearno.models.dtos;

import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;

@Data
@NoArgsConstructor
@AllArgsConstructor
public class ContentBlock {
    private String type;
    private String path;
    private String quizId;
}
