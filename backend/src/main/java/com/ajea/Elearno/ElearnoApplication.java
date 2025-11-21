package com.ajea.Elearno;

import com.ajea.Elearno.models.LearningMaterial;
import com.ajea.Elearno.repository.LearningMaterialRepository;
import org.springframework.boot.CommandLineRunner;
import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.context.annotation.Bean;

@SpringBootApplication
public class ElearnoApplication {

	public static void main(String[] args) {
		SpringApplication.run(ElearnoApplication.class, args);
	}
}
